<?php

namespace App\Http\Controllers;

use App\Models\AiGeneratedSite;
use App\Models\HostingAccount;
use App\Services\GeminiSiteBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiSiteBuilderController extends Controller
{
    /**
     * Galeria e listagem de sites gerados.
     */
    public function index(Request $request)
    {
        $query = AiGeneratedSite::with(['hostingAccount.client', 'user'])
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('business_name', 'like', "%{$s}%")
                  ->orWhere('niche', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sites = $query->paginate(12)->withQueryString();

        $kpis = [
            'total' => AiGeneratedSite::count(),
            'published' => AiGeneratedSite::where('status', AiGeneratedSite::STATUS_PUBLISHED)->count(),
            'drafts' => AiGeneratedSite::where('status', AiGeneratedSite::STATUS_DRAFT)->count(),
        ];

        return view('ai-builder.index', compact('sites', 'kpis'));
    }

    /**
     * Exibe o formulário Wizard de criação.
     */
    public function create(Request $request)
    {
        $hostingAccounts = HostingAccount::with('client')
            ->where('status', HostingAccount::STATUS_ACTIVE)
            ->orderBy('domain')
            ->get();

        $selectedHostingId = $request->query('hosting_id');

        return view('ai-builder.create', compact('hostingAccounts', 'selectedHostingId'));
    }

    /**
     * Processa o Wizard e gera a página com a IA Gemini.
     */
    public function store(Request $request, GeminiSiteBuilderService $service)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:150'],
            'niche' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1500'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'style' => ['required', 'string', 'in:dark_frosted,clean_minimal,corporate_blue,luxury_gold,vibrant_modern'],
            'sections' => ['nullable', 'array'],
            'sections.*' => ['string'],
            'hosting_account_id' => ['nullable', 'exists:hosting_accounts,id'],
        ]);

        try {
            $html = $service->generateSite($validated);

            $site = AiGeneratedSite::create([
                'user_id' => Auth::id(),
                'hosting_account_id' => $validated['hosting_account_id'] ?? null,
                'title' => $validated['business_name'],
                'business_name' => $validated['business_name'],
                'niche' => $validated['niche'],
                'description' => $validated['description'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'style' => $validated['style'],
                'sections' => $validated['sections'] ?? ['hero', 'benefits', 'services', 'testimonials', 'faq', 'cta', 'contact'],
                'generated_html' => $html,
                'prompt_history' => [
                    [
                        'action' => 'initial_generation',
                        'prompt_data' => $validated,
                        'created_at' => now()->toIso8601String(),
                    ]
                ],
                'status' => AiGeneratedSite::STATUS_DRAFT,
                'revisions_count' => 1,
            ]);

            return redirect()->route('ai-builder.studio', $site)
                ->with('success', 'Website gerado com sucesso pela IA Gemini! Visualize e ajuste no Studio abaixo.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'gemini_error' => 'Não foi possível gerar o site no momento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Studio Interativo: Preview responsivo e chat de refinamento.
     */
    public function studio(AiGeneratedSite $aiSite)
    {
        $aiSite->load(['hostingAccount.client', 'user']);
        return view('ai-builder.studio', compact('aiSite'));
    }

    /**
     * Refina o site via IA com instrução do usuário (AJAX ou Form).
     */
    public function refine(Request $request, AiGeneratedSite $aiSite, GeminiSiteBuilderService $service)
    {
        $request->validate([
            'instruction' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $newHtml = $service->refineSite($aiSite->generated_html, $request->instruction);

            $history = $aiSite->prompt_history ?? [];
            $history[] = [
                'action' => 'refine',
                'instruction' => $request->instruction,
                'created_at' => now()->toIso8601String(),
            ];

            $aiSite->update([
                'generated_html' => $newHtml,
                'revisions_count' => $aiSite->revisions_count + 1,
                'prompt_history' => $history,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Site refinado com sucesso!',
                    'revisions_count' => $aiSite->revisions_count,
                    'preview_url' => route('ai-builder.preview', $aiSite) . '?t=' . time(),
                ]);
            }

            return redirect()->route('ai-builder.studio', $aiSite)
                ->with('success', 'Alteração aplicada com sucesso pela IA!');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao refinar com a IA: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors(['gemini_error' => 'Erro ao refinar: ' . $e->getMessage()]);
        }
    }

    /**
     * Rota de preview isolada do site gerado.
     */
    public function preview(AiGeneratedSite $aiSite)
    {
        return response($aiSite->generated_html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    }

    /**
     * Publica o site no domínio/hospedagem vinculada.
     */
    public function publish(AiGeneratedSite $aiSite, GeminiSiteBuilderService $service)
    {
        try {
            $publicUrl = $service->publishToHosting($aiSite);

            return redirect()->route('ai-builder.studio', $aiSite)
                ->with('success', "Website publicado com sucesso! Acesse em: {$publicUrl}");

        } catch (\Exception $e) {
            return back()->withErrors(['publish_error' => 'Erro ao publicar site: ' . $e->getMessage()]);
        }
    }

    /**
     * Download do arquivo index.html.
     */
    public function downloadHtml(AiGeneratedSite $aiSite)
    {
        $filename = 'index.html';
        return response()->streamDownload(function () use ($aiSite) {
            echo $aiSite->generated_html;
        }, $filename, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    /**
     * Download do pacote ZIP completo.
     */
    public function downloadZip(AiGeneratedSite $aiSite, GeminiSiteBuilderService $service)
    {
        $zipPath = $service->exportZip($aiSite);
        $downloadName = 'site-' . ($aiSite->hostingAccount ? $aiSite->hostingAccount->domain : 'export-' . $aiSite->id) . '.zip';

        return response()->download($zipPath, $downloadName);
    }

    /**
     * Exclui suavemente o site.
     */
    public function destroy(AiGeneratedSite $aiSite)
    {
        $aiSite->delete();

        return redirect()->route('ai-builder.index')
            ->with('success', 'Site removido com sucesso.');
    }
}
