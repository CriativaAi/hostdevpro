<?php

namespace App\Services;

use App\Models\AiGeneratedSite;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class GeminiSiteBuilderService
{
    protected ?string $apiKey;
    protected array $models;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->models = [
            'gemini-3.5-flash',
            'gemini-flash-latest',
            'gemini-3.7-flash',
            'gemini-3.5-flash-lite',
            'gemini-3.6-flash',
        ];
    }

    /**
     * Gera um website completo (HTML5 + Tailwind) via Gemini API.
     */
    public function generateSite(array $data): string
    {
        $businessName = $data['business_name'] ?? 'Minha Empresa';
        $niche = $data['niche'] ?? 'Negócios e Serviços';
        $description = $data['description'] ?? 'Serviços de excelência com atendimento ágil e especializado.';
        $rawPhone = $data['whatsapp'] ?? '11921381308';
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = substr($cleanPhone, 1);
        }
        if (strlen($cleanPhone) <= 11 && !str_starts_with($cleanPhone, '55')) {
            $cleanPhone = '55' . $cleanPhone;
        }

        $style = $data['style'] ?? 'dark_frosted';
        $sections = $data['sections'] ?? ['hero', 'benefits', 'services', 'testimonials', 'faq', 'cta', 'contact'];

        $styleGuide = match ($style) {
            'clean_minimal' => "Tema Claro, Clean e Minimalista: Fundo claro (#F8FAFC), tipografia refinada em ardósia escura (#0F172A), cartões com bordas sutis (#E2E8F0), toques de azul cobalto (#2563EB) ou esmeralda, sensação de leveza e clareza.",
            'corporate_blue' => "Tema Corporativo & Tech: Fundo em azul marinho profundo (#0B132B / #1C2541), acentos em azul ciano elétrico (#00F0FF) e safira, design robusto de alta autoridade, perfeito para empresas de software, consultoria e negócios B2B.",
            'luxury_gold' => "Tema Elegante & Premium: Fundo preto ônix profundo (#0A0A0A) com detalhes em dourado metálico (#D4AF37 / #F59E0B), tipografia nobre, bordas finas douradas translúcidas, perfeito para joalherias, escritórios boutique, clínicas estéticas e alta gastronomia.",
            'vibrant_modern' => "Tema Vibrante & Criativo: Gradientes energéticos de púrpura, rosa neon e turquesa, modo escuro vibrante com elementos flutuantes, visual de startup moderna, ideal para agências, eventos, cursos e infoprodutos.",
            default => "Tema Dark Frosted Glass (HostDevPro): Fundo escuro profundo (#020617 ou #090D16), cartões de vidro fosco (backdrop-blur-xl bg-white/[0.06] border border-white/10), acentos neon em esmeralda (#10B981) e ciano (#06B6D4), cantos arredondados (rounded-2xl e rounded-3xl), sombras suaves glow."
        };

        $sectionsDescription = implode(', ', $sections);

        $prompt = <<<EOT
Você é um Engenheiro de Software Sênior e Diretor de Arte Web especialista em UI/UX de alto padrão e páginas de conversão que geram vendas.
Crie um website profissional completo, moderno, responsivo e deslumbrante de UMA PÁGINA (Single-Page Landing Page) para o seguinte cliente:

- NOME DA EMPRESA/PROJETO: {$businessName}
- RAMO / NICHO: {$niche}
- DESCRIÇÃO E DIFERENCIAIS: {$description}
- WHATSAPP DE ATENDIMENTO: {$cleanPhone} (Link direto de conversão: https://wa.me/{$cleanPhone}?text=Ol%C3%A1,%20gostaria%20de%20um%20or%C3%A7amento!)
- ESTILO VISUAL: {$styleGuide}
- SEÇÕES A INCLUIR: {$sectionsDescription}

DIRETRIZES TÉCNICAS E DE DESIGN (SIGA ESTRITAMENTE):
1. Retorne APENAS o código HTML5 completo e válido, iniciando em <!DOCTYPE html> e fechando OBRIGATORIAMENTE em </html>. NÃO envolva em blocos de markdown (não use ```html ou ```). Não adicione nenhum texto explicativo antes ou depois do HTML.
2. Utilize Tailwind CSS via CDN oficial (<script src="https://cdn.tailwindcss.com"></script>).
3. Importe fontes modernas do Google Fonts no <head> (ex: Plus Jakarta Sans ou Inter) e aplique na página.
4. O layout deve ser 100% responsivo para Mobile, Tablet e Desktop. Inclua menu de navegação responsivo com botão hambúrguer funcional via JavaScript embutido simples.
5. HERO SECTION: Título de alto impacto focado no benefício do cliente, subtítulo persuasivo, selo de credibilidade ("+ de 500 clientes atendidos", "Garantia de Qualidade"), e botões chamativos de CTA (WhatsApp e Serviços).
6. SEÇÃO DE SERVIÇOS / PRODUTOS: Grid moderno de 3 a 4 colunas com ícones SVG inline minimalistas e limpos, títulos atraentes e descrições personalizadas para o nicho de {$niche}.
7. SEÇÃO DE DEPOIMENTOS: 3 depoimentos realistas com nomes brasileiros, fotos ou avatares elegantes, 5 estrelas e relatos de satisfação.
8. FAQ (PERGUNTAS FREQUENTES): 4 perguntas pertinentes com mecanismo simples de accordion (clicar para abrir/fechar via JS embutido).
9. BOTÃO FLUTUANTE WHATSAPP: Botão fixo no canto inferior direito com ícone do WhatsApp, efeito pulsar e link direto para https://wa.me/{$cleanPhone}.
10. RODAPÉ: Informações completas de contato, copyright do ano atual para {$businessName}, links rápidos e um discreto "Hospedado com performance na HostDevPro".
11. EFICIÊNCIA DE CÓDIGO: Use SVGs inline concisos (path simples de ícones comuns como telefone, estrela, check, escudo, raio) para garantir carregamento instantâneo.
12. OBRIGATÓRIO: A resposta DEVE ir até o final do documento, fechando as tags </body> e </html>.
EOT;

        return $this->callGeminiApi($prompt);
    }

    /**
     * Refina ou altera o website existente com base em uma instrução do usuário.
     */
    public function refineSite(string $currentHtml, string $instruction): string
    {
        $prompt = <<<EOT
Você é um especialista em Frontend e UI/UX.
Abaixo está o código HTML completo de uma landing page existente:

--- CÓDIGO ATUAL INÍCIO ---
{$currentHtml}
--- CÓDIGO ATUAL FIM ---

O cliente solicitou a seguinte alteração/melhoria:
"{$instruction}"

INSTRUÇÕES:
1. Aplique a alteração solicitada com perfeição, preservando toda a estrutura, estilo, responsividade e funcionalidades existentes.
2. Retorne APENAS o código HTML5 completo atualizado, começando em <!DOCTYPE html> e terminando em </html>.
3. NÃO use blocos de código markdown (não use ```html nem ```). Apenas o código HTML puro fechando em </html>.
EOT;

        return $this->callGeminiApi($prompt);
    }

    /**
     * Chama a API do Gemini com fallback transparente entre modelos.
     */
    protected function callGeminiApi(string $prompt): string
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('A chave da API do Google Gemini (GEMINI_API_KEY) não está configurada no ambiente.');
        }

        $lastError = null;

        foreach ($this->models as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;

                $response = Http::timeout(60)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topP' => 0.95,
                        'maxOutputTokens' => 16384,
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    if (!empty($text)) {
                        return $this->sanitizeHtml($text);
                    }
                }

                $errorMessage = $response->json('error.message') ?? 'Erro ' . $response->status();
                $lastError = $errorMessage;

                Log::warning("Gemini modelo {$model} retornou erro, tentando próximo modelo...", [
                    'status' => $response->status(),
                    'error' => $errorMessage,
                ]);

            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                Log::warning("Exceção ao chamar Gemini modelo {$model}: " . $e->getMessage());
            }
        }

        throw new \RuntimeException('Falha ao comunicar com o Google Gemini: ' . ($lastError ?? 'Nenhum modelo respondeu com sucesso.'));
    }

    /**
     * Remove eventuais delimitadores markdown e garante HTML puro fechado.
     */
    public function sanitizeHtml(string $raw): string
    {
        $cleaned = trim($raw);

        // Remove ```html no início e ``` no fim
        if (preg_match('/^```(?:html)?\s*(.*)\s*```$/is', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        } else {
            // Remove qualquer prefixo antes de <!DOCTYPE html>
            $doctypePos = stripos($cleaned, '<!DOCTYPE html');
            if ($doctypePos !== false) {
                $cleaned = substr($cleaned, $doctypePos);
            }
            // Remove qualquer sufixo após </html>
            $htmlEndPos = stripos($cleaned, '</html>');
            if ($htmlEndPos !== false) {
                $cleaned = substr($cleaned, 0, $htmlEndPos + 7);
            }
        }

        // Se </html> não estiver presente (caso raro de corte), fecha graciosamente
        if (!str_contains($cleaned, '</html>')) {
            if (!str_contains($cleaned, '</body>')) {
                $cleaned .= "\n</body>";
            }
            $cleaned .= "\n</html>";
        }

        return $cleaned;
    }

    /**
     * Publica o site no armazenamento e atualiza o modelo.
     */
    public function publishToHosting(AiGeneratedSite $site): string
    {
        $domain = $site->hostingAccount ? $site->hostingAccount->domain : ('site-' . $site->id . '.hostdevpro.app.br');
        $publicRelativeDir = "published_sites/{$domain}";
        $fullPath = public_path($publicRelativeDir);

        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        File::put("{$fullPath}/index.html", $site->generated_html);

        $site->update([
            'status' => AiGeneratedSite::STATUS_PUBLISHED,
            'published_at' => now(),
            'published_path' => "{$publicRelativeDir}/index.html",
        ]);

        return url("published_sites/{$domain}/index.html");
    }

    /**
     * Gera um arquivo .ZIP pronto para download com o index.html e guia de uso.
     */
    public function exportZip(AiGeneratedSite $site): string
    {
        $zipName = 'site-' . ($site->hostingAccount ? $site->hostingAccount->domain : 'export-' . $site->id) . '.zip';
        $tempDir = storage_path('app/temp_exports');
        
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zipPath = "{$tempDir}/{$zipName}";
        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('index.html', $site->generated_html);
            
            $readme = "Website gerado automaticamente com Google Gemini na HostDevPro\n";
            $readme .= "Empresa: {$site->business_name}\n";
            $readme .= "Nicho: {$site->niche}\n";
            $readme .= "Data: " . now()->format('d/m/Y H:i:s') . "\n\n";
            $readme .= "COMO PUBLICAR NA SUA HOSPEDAGEM (PLESK / CPANEL / APACHE / NGINX):\n";
            $readme .= "1. Acesse o gerenciador de arquivos da sua hospedagem.\n";
            $readme .= "2. Entre na pasta raiz do seu domínio (geralmente public_html ou httpdocs).\n";
            $readme .= "3. Faça o upload do arquivo index.html contido neste arquivo ZIP.\n";
            $readme .= "4. Pronto! Seu website estará imediatamente no ar no seu domínio.\n";

            $zip->addFromString('LEIA-ME.txt', $readme);
            $zip->close();
        }

        return $zipPath;
    }
}
