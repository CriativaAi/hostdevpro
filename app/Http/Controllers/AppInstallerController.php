<?php

namespace App\Http\Controllers;

use App\Models\HostingAccount;
use App\Services\AppInstallerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppInstallerController extends Controller
{
    public function __construct(
        protected AppInstallerService $installer
    ) {}

    /**
     * Retorna o catálogo de aplicações disponíveis para a hospedagem.
     */
    public function catalog(HostingAccount $hosting): JsonResponse
    {
        $apps = $this->installer->getCatalog($hosting);

        return response()->json([
            'success' => true,
            'domain' => $hosting->domain,
            'apps' => $apps,
        ]);
    }

    /**
     * Executa a instalação 1-clique do aplicativo selecionado.
     */
    public function install(HostingAccount $hosting, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_id' => 'required|string|in:wordpress,sales_lp,laravel,coming_soon',
            'site_title' => 'nullable|string|max:150',
            'admin_user' => 'nullable|string|max:50',
            'admin_email' => 'nullable|email',
            'admin_pass' => 'nullable|string|min:6',
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:30',
            'clean_root' => 'nullable|boolean',
        ]);

        try {
            $result = $this->installer->install(
                $hosting,
                $validated['app_id'],
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Aplicação instalada com sucesso!',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            Log::error("Erro na instalação da aplicação {$validated['app_id']} em {$hosting->domain}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao instalar aplicação: ' . $e->getMessage(),
            ], 500);
        }
    }
}
