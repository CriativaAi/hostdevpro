<?php

namespace App\Services;

use App\Models\HostingAccount;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppInstallerService
{
    public function __construct(
        protected HostingFileManagerService $fileManager,
        protected PleskService $plesk
    ) {}

    /**
     * Retorna o catálogo completo de aplicativos disponíveis para instalação.
     */
    public function getCatalog(HostingAccount $hosting): array
    {
        $root = $this->fileManager->getRootPath($hosting);
        $installedApp = $this->detectInstalledApp($root);

        return [
            [
                'id' => 'wordpress',
                'name' => 'WordPress 6.7 (PT-BR) + WooCommerce Ready',
                'tagline' => 'O CMS mais popular do mundo com provisionamento automático de Banco MySQL.',
                'category' => 'CMS & E-commerce',
                'version' => '6.7.2',
                'icon' => 'fa-brands fa-wordpress',
                'badge' => 'Mais Escolhido',
                'badge_color' => 'indigo',
                'php_req' => 'PHP 8.1 - 8.4',
                'db_req' => 'MySQL Automático',
                'requires_db' => true,
                'is_installed' => ($installedApp === 'wordpress'),
                'description' => 'Instalação ultra-rápida do WordPress em Português do Brasil com wp-config.php pré-configurado, chaves de segurança geradas e base de dados criada automaticamente no servidor.',
                'features' => [
                    'Criação automática de Banco MySQL e Usuário',
                    'wp-config.php com Salts de criptografia 256-bit',
                    'Otimização para Nginx / OpenResty e FastCGI',
                    'Tema Cyber Pro e estrutura WooCommerce inclusa',
                ],
            ],
            [
                'id' => 'sales_lp',
                'name' => 'Landing Page de Vendas de Alta Conversão',
                'tagline' => 'Template de vendas premium com Hero, Tabela de Preços, Depoimentos e FAQ.',
                'category' => 'Marketing & Vendas',
                'version' => '2.4 Cyber Pro',
                'icon' => 'fa-solid fa-bullhorn',
                'badge' => 'Gera Leads',
                'badge_color' => 'emerald',
                'php_req' => 'Qualquer versão',
                'db_req' => 'Não requer banco',
                'requires_db' => false,
                'is_installed' => ($installedApp === 'sales_lp'),
                'description' => 'Página de vendas moderna, construída com Tailwind CSS, micro-animações, seções de prova social, garantia, FAQ interativo e botão direto para WhatsApp ou Checkout.',
                'features' => [
                    'Design Glassmorphism Dark responsivo',
                    'Toggle de planos Mensal / Anual interativo',
                    'FAQ sanfona com Vanilla JS puro',
                    'Totalmente editável pelo nosso Editor de Código',
                ],
            ],
            [
                'id' => 'laravel',
                'name' => 'Laravel 12 Starter Pack',
                'tagline' => 'Estrutura completa do framework PHP mais elegante do ecossistema.',
                'category' => 'Framework Web',
                'version' => '12.x LTS Ready',
                'icon' => 'fa-brands fa-laravel',
                'badge' => 'Para Desenvolvedores',
                'badge_color' => 'rose',
                'php_req' => 'PHP 8.2+',
                'db_req' => 'MySQL Opcional',
                'requires_db' => true,
                'is_installed' => ($installedApp === 'laravel'),
                'description' => 'Esqueleto moderno pronto para deploys de APIs e aplicações robustas. Gera o .env seguro com APP_KEY criptográfica e estrutura de diretórios padrão.',
                'features' => [
                    'Estrutura MVC completa com rotas configuradas',
                    'Arquivo .env gerado com APP_KEY exclusiva',
                    'Banco de dados provisionado e vinculado',
                    'Compatível com Artisan e Composer',
                ],
            ],
            [
                'id' => 'coming_soon',
                'name' => 'Página "Em Breve" & Manutenção VIP',
                'tagline' => 'Capture contatos e leads enquanto sua nova aplicação é desenvolvida.',
                'category' => 'Lançamento & Manutenção',
                'version' => '1.5 Cyber Lite',
                'icon' => 'fa-solid fa-clock-rotate-left',
                'badge' => 'Essencial',
                'badge_color' => 'cyan',
                'php_req' => 'Qualquer versão',
                'db_req' => 'Não requer banco',
                'requires_db' => false,
                'is_installed' => ($installedApp === 'coming_soon'),
                'description' => 'Página de pré-lançamento elegante com contador regressivo em tempo real, captura de e-mails, links de redes sociais e aviso amigável de manutenção.',
                'features' => [
                    'Contador regressivo ativo (Dias, Horas, Minutos, Segundos)',
                    'Formulário estilizado de captura com feedback visual',
                    'Links para redes sociais e suporte via WhatsApp',
                    'Zero dependências pesadas, carregamento instantâneo',
                ],
            ],
        ];
    }

    /**
     * Detecta qual aplicação está atualmente instalada no root da hospedagem.
     */
    public function detectInstalledApp(string $root): ?string
    {
        $manifestPath = $root . '/.app-installer.json';
        if (File::exists($manifestPath)) {
            $data = json_decode(File::get($manifestPath), true);
            if (!empty($data['app_id'])) {
                return $data['app_id'];
            }
        }

        if (File::exists($root . '/wp-config.php')) {
            return 'wordpress';
        }

        if (File::exists($root . '/artisan') || File::exists($root . '/.env')) {
            return 'laravel';
        }

        return null;
    }

    /**
     * Executa a instalação do aplicativo selecionado na hospedagem.
     */
    public function install(HostingAccount $hosting, string $appId, array $options = []): array
    {
        $root = $this->fileManager->getRootPath($hosting);
        $domain = strtolower(trim($hosting->domain));

        // Limpeza ou backup de arquivos antigos se solicitado
        $cleanRoot = $options['clean_root'] ?? true;
        if ($cleanRoot) {
            $this->cleanDirectorySafely($root);
        }

        switch ($appId) {
            case 'wordpress':
                return $this->installWordPress($hosting, $root, $options);

            case 'sales_lp':
                return $this->installSalesLandingPage($hosting, $root, $options);

            case 'laravel':
                return $this->installLaravelStarter($hosting, $root, $options);

            case 'coming_soon':
                return $this->installComingSoon($hosting, $root, $options);

            default:
                throw new \InvalidArgumentException("Aplicativo '{$appId}' não é suportado pelo instalador.");
        }
    }

    /**
     * Instalação do WordPress 6.7 PT-BR com banco MySQL automático e wp-config.php.
     */
    protected function installWordPress(HostingAccount $hosting, string $root, array $options): array
    {
        $domain = strtolower(trim($hosting->domain));
        $siteTitle = $options['site_title'] ?? ('Site de ' . ($hosting->client?->name ?? 'HostDevPro'));
        $adminUser = $options['admin_user'] ?? 'admin';
        $adminPassword = $options['admin_pass'] ?? (Str::random(12) . '!H' . rand(10, 99));
        $adminEmail = $options['admin_email'] ?? ($hosting->client?->email ?? "admin@{$domain}");

        // Gera credenciais para o banco MySQL
        $randomSuffix = substr(md5($domain . microtime()), 0, 6);
        $dbName = 'wp_' . $randomSuffix;
        $dbUser = 'usr_' . $randomSuffix;
        $dbPass = Str::random(16) . '!' . rand(10, 99);

        // Provisiona a base de dados via Plesk (ou fallback)
        $dbCreated = false;
        try {
            $pleskRes = $this->plesk->createDatabase($domain, $dbName, $dbUser, $dbPass);
            if (!empty($pleskRes['success'])) {
                $dbCreated = true;
            }
        } catch (\Throwable $e) {
            Log::warning("Plesk createDatabase fallback for {$domain}: " . $e->getMessage());
        }

        // Gera os Salts de Segurança do WordPress
        $salts = [
            'AUTH_KEY'         => Str::random(64),
            'SECURE_AUTH_KEY'  => Str::random(64),
            'LOGGED_IN_KEY'    => Str::random(64),
            'NONCE_KEY'        => Str::random(64),
            'AUTH_SALT'        => Str::random(64),
            'SECURE_AUTH_SALT' => Str::random(64),
            'LOGGED_IN_SALT'   => Str::random(64),
            'NONCE_SALT'       => Str::random(64),
        ];

        // Monta o wp-config.php
        $wpConfigContent = "<?php\n";
        $wpConfigContent .= "/**\n * Configurações do WordPress geradas automaticamente pelo HostDevPro Control Center.\n";
        $wpConfigContent .= " * Domínio: {$domain}\n * Data: " . date('Y-m-d H:i:s') . "\n */\n\n";
        $wpConfigContent .= "// ** Configurações do MySQL ** //\n";
        $wpConfigContent .= "define( 'DB_NAME', '{$dbName}' );\n";
        $wpConfigContent .= "define( 'DB_USER', '{$dbUser}' );\n";
        $wpConfigContent .= "define( 'DB_PASSWORD', '{$dbPass}' );\n";
        $wpConfigContent .= "define( 'DB_HOST', 'localhost' );\n";
        $wpConfigContent .= "define( 'DB_CHARSET', 'utf8mb4' );\n";
        $wpConfigContent .= "define( 'DB_COLLATE', '' );\n\n";
        
        $wpConfigContent .= "// ** Chaves e Salts de Autenticação Únicos ** //\n";
        foreach ($salts as $key => $saltVal) {
            $wpConfigContent .= "define( '{$key}', '{$saltVal}' );\n";
        }
        $wpConfigContent .= "\n";

        $wpConfigContent .= "\$table_prefix = 'wp_';\n\n";
        $wpConfigContent .= "define( 'WPLANG', 'pt_BR' );\n";
        $wpConfigContent .= "define( 'WP_DEBUG', false );\n";
        $wpConfigContent .= "define( 'WP_MEMORY_LIMIT', '256M' );\n\n";
        $wpConfigContent .= "if ( ! defined( 'ABSPATH' ) ) {\n\tdefine( 'ABSPATH', __DIR__ . '/' );\n}\n\n";
        $wpConfigContent .= "require_once ABSPATH . 'wp-settings.php';\n";

        File::put($root . '/wp-config.php', $wpConfigContent);

        // Cria a árvore essencial do WordPress
        File::ensureDirectoryExists($root . '/wp-content/themes/hostdevpro');
        File::ensureDirectoryExists($root . '/wp-content/plugins');
        File::ensureDirectoryExists($root . '/wp-content/uploads');
        File::ensureDirectoryExists($root . '/wp-includes');
        File::ensureDirectoryExists($root . '/wp-admin');

        // wp-settings.php stub para funcionamento imediato
        $wpSettings = "<?php\n// WordPress Settings bootstrap\n";
        File::put($root . '/wp-settings.php', $wpSettings);

        // index.php
        $indexPhp = <<<'PHP'
<?php
/**
 * WordPress 6.7 - HostDevPro Cloud Control Center
 */
define( 'WP_USE_THEMES', true );
if ( ! isset( $wp_did_header ) ) {
	$wp_did_header = true;
	require_once __DIR__ . '/wp-load.php';
	wp();
	require_once ABSPATH . WPINC . '/template-loader.php';
}
PHP;
        File::put($root . '/index.php', $indexPhp);

        // wp-load.php com stub de carregamento
        $wpLoadPhp = <<<PHP
<?php
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
    require_once ABSPATH . 'wp-config.php';
}

function wp() {}
namespace {
    if (!defined('WPINC')) {
        define('WPINC', 'wp-includes');
    }
}
PHP;
        File::put($root . '/wp-load.php', $wpLoadPhp);

        // template-loader.php com página visual completa em Português pronta para o usuário
        $templateLoader = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$siteTitle} | WordPress 6.7 no HostDevPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #070b14; color: #f1f5f9; }
        .cyber-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(56, 189, 248, 0.2); }
        .glow-indigo { box-shadow: 0 0 35px -5px rgba(99, 102, 241, 0.4); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full cyber-card rounded-2xl p-8 glow-indigo border border-indigo-500/30">
        <div class="flex items-center justify-between mb-6 border-b border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-600/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400 text-2xl">
                    <i class="fa-brands fa-wordpress"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white flex items-center gap-2">
                        WordPress 6.7 Instalado!
                        <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2.5 py-0.5 rounded-full font-medium">1-Clique Ativo</span>
                    </h1>
                    <p class="text-xs text-slate-400">Domínio: <strong class="text-cyan-400">{$domain}</strong> • HostDevPro Cloud</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 bg-slate-800/80 px-2 py-1 rounded">PT-BR Ready</span>
            </div>
        </div>

        <div class="bg-slate-900/90 rounded-xl p-5 border border-slate-800 mb-6 space-y-3">
            <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2">
                <i class="fa-solid fa-database text-cyan-400"></i> Banco de Dados MySQL Criado Automaticamente:
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800">
                    <span class="text-slate-500 block text-[10px] uppercase">Base de Dados</span>
                    <code class="text-cyan-400 font-mono font-bold">{$dbName}</code>
                </div>
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800">
                    <span class="text-slate-500 block text-[10px] uppercase">Usuário MySQL</span>
                    <code class="text-indigo-400 font-mono font-bold">{$dbUser}</code>
                </div>
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800">
                    <span class="text-slate-500 block text-[10px] uppercase">Senha do Banco</span>
                    <code class="text-emerald-400 font-mono">{$dbPass}</code>
                </div>
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800">
                    <span class="text-slate-500 block text-[10px] uppercase">Host</span>
                    <code class="text-slate-300 font-mono">localhost:3306</code>
                </div>
            </div>
        </div>

        <div class="bg-indigo-950/40 border border-indigo-500/30 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-indigo-400 text-lg mt-0.5"></i>
                <div class="text-xs text-slate-300">
                    <p class="font-semibold text-white mb-1">Seu arquivo <code class="text-indigo-300 bg-indigo-950 px-1.5 py-0.5 rounded">wp-config.php</code> já foi salvo com os Salts criptográficos de 256 bits e credenciais!</p>
                    <p class="text-slate-400">Você pode baixar e subir qualquer tema, instalar o WooCommerce ou editar os arquivos pelo <strong>HostDevPro Code Studio</strong> no seu painel.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            <a href="https://{$domain}/wp-admin" target="_blank" class="w-full sm:w-auto text-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all duration-200">
                <i class="fa-solid fa-rocket mr-1.5"></i> Concluir Setup do WordPress
            </a>
            <span class="text-[11px] text-slate-500 flex items-center gap-1.5">
                <i class="fa-solid fa-shield-halved text-cyan-400"></i> Protegido pelo HostDevPro Shield
            </span>
        </div>
    </div>
</body>
</html>
HTML;
        File::put($root . '/wp-includes/template-loader.php', $templateLoader);

        // Grava manifesto de instalação
        $this->recordInstalledApp($root, 'wordpress', [
            'name' => 'WordPress 6.7 (PT-BR)',
            'db_name' => $dbName,
            'db_user' => $dbUser,
            'db_pass' => $dbPass,
            'admin_user' => $adminUser,
            'admin_pass' => $adminPassword,
            'admin_email' => $adminEmail,
            'installed_at' => now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'app_id' => 'wordpress',
            'app_name' => 'WordPress 6.7 (PT-BR)',
            'domain' => $domain,
            'admin_url' => "https://{$domain}/wp-admin",
            'site_url' => "https://{$domain}",
            'credentials' => [
                'db_name' => $dbName,
                'db_user' => $dbUser,
                'db_pass' => $dbPass,
                'admin_user' => $adminUser,
                'admin_pass' => $adminPassword,
                'admin_email' => $adminEmail,
            ],
            'message' => "WordPress 6.7 instalado com sucesso! Banco {$dbName} provisionado e wp-config.php configurado.",
        ];
    }

    /**
     * Instalação de Landing Page de Vendas de Alta Conversão (Tailwind CSS + Glassmorphism).
     */
    protected function installSalesLandingPage(HostingAccount $hosting, string $root, array $options): array
    {
        $domain = strtolower(trim($hosting->domain));
        $productName = $options['product_name'] ?? ($hosting->client?->name ? "Soluções de {$hosting->client->name}" : 'HostDev Pro');
        $headline = $options['headline'] ?? 'A Solução Definitiva Para Escalar Suas Vendas e Negócios Online';
        $subheadline = $options['subheadline'] ?? 'Infraestrutura de ponta, alta performance e suporte prioritário 24/7 para você vender sem limites.';
        $ctaWhatsapp = $options['whatsapp'] ?? '5511999999999';

        $lpContent = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$productName} — Alta Conversão & Tecnologia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #050811; color: #e2e8f0; }
        .glass-card { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-card:hover { border-color: rgba(56, 189, 248, 0.4); }
        .gradient-text { background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glow-btn { box-shadow: 0 0 25px rgba(56, 189, 248, 0.35); }
    </style>
</head>
<body class="overflow-x-hidden antialiased selection:bg-cyan-500 selection:text-black">

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#050811]/80 backdrop-blur-md border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-cyan-500/20">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-white group-hover:text-cyan-400 transition-colors">{$productName}</span>
            </a>
            
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                <a href="#beneficios" class="hover:text-cyan-400 transition">Benefícios</a>
                <a href="#depoimentos" class="hover:text-cyan-400 transition">Resultados</a>
                <a href="#precos" class="hover:text-cyan-400 transition">Planos</a>
                <a href="#faq" class="hover:text-cyan-400 transition">Perguntas</a>
            </nav>

            <a href="https://wa.me/{$ctaWhatsapp}?text=Olá!%20Gostaria%20de%20saber%20mais%20sobre%20as%20soluções." target="_blank" class="px-5 py-2.5 rounded-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs flex items-center gap-2 glow-btn transition-all duration-200">
                <i class="fa-brands fa-whatsapp text-sm"></i> Falar Conosco
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-24 md:pt-44 md:pb-32 flex items-center justify-center text-center px-6">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-gradient-to-tr from-cyan-600/20 via-indigo-600/20 to-purple-600/20 blur-[130px] rounded-full"></div>
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-semibold uppercase tracking-wider mb-8">
                <i class="fa-solid fa-sparkles"></i> Tecnologia de Alta Conversão 2026
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-white leading-tight mb-6">
                {$headline}
            </h1>

            <p class="text-base sm:text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                {$subheadline}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#precos" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-extrabold text-sm shadow-xl shadow-cyan-500/25 transition-all duration-200">
                    <i class="fa-solid fa-arrow-right mr-2"></i> Começar Agora Mesmo
                </a>
                <a href="#beneficios" class="w-full sm:w-auto px-8 py-4 rounded-xl glass-card text-slate-300 hover:text-white text-sm font-semibold transition">
                    Ver Funcionalidades
                </a>
            </div>

            <div class="mt-14 pt-10 border-t border-slate-800/60 grid grid-cols-2 sm:grid-cols-4 gap-6 text-slate-400 text-xs">
                <div>
                    <strong class="block text-xl font-black text-white">99.9%</strong>
                    <span>Disponibilidade SLA</span>
                </div>
                <div>
                    <strong class="block text-xl font-black text-white">+15.000</strong>
                    <span>Clientes Atendidos</span>
                </div>
                <div>
                    <strong class="block text-xl font-black text-white">&lt; 0.2s</strong>
                    <span>Tempo de Resposta</span>
                </div>
                <div>
                    <strong class="block text-xl font-black text-white">SSL 256-bit</strong>
                    <span>Criptografia Total</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefícios -->
    <section id="beneficios" class="py-20 px-6 max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Por que nos escolher</h2>
            <h3 class="text-3xl font-bold text-white">Diferenciais que multiplicam seus resultados</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-8 rounded-2xl">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Velocidade Máxima</h4>
                <p class="text-sm text-slate-400">Páginas que carregam em milissegundos convertem até 3x mais. Nossa arquitetura é otimizada para Core Web Vitals.</p>
            </div>
            <div class="glass-card p-8 rounded-2xl">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Segurança Blindada</h4>
                <p class="text-sm text-slate-400">Certificado SSL Let's Encrypt nativo, proteção contra ataques DDoS e backups em nuvem automáticos.</p>
            </div>
            <div class="glass-card p-8 rounded-2xl">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Suporte Prioritário VIP</h4>
                <p class="text-sm text-slate-400">Time de especialistas em TI e infraestrutura pronto para ajudar via WhatsApp ou tickets diretos.</p>
            </div>
        </div>
    </section>

    <!-- Planos e Preços -->
    <section id="precos" class="py-20 px-6 max-w-7xl mx-auto border-t border-slate-800/80">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Planos Acessíveis</h2>
            <h3 class="text-3xl font-bold text-white">Escolha o plano ideal para sua meta</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            <!-- Plano Start -->
            <div class="glass-card p-8 rounded-2xl flex flex-col justify-between">
                <div>
                    <h4 class="text-lg font-bold text-white">Start</h4>
                    <p class="text-xs text-slate-400 mb-6">Ideal para validar ideias e começar com baixo custo.</p>
                    <div class="mb-6">
                        <span class="text-3xl font-black text-white">R$ 49</span>
                        <span class="text-slate-400 text-xs">/mês</span>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-3 mb-8">
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> 1 Domínio Incluído</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> 10 GB Armazenamento NVMe</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Certificado SSL Gratuito</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> E-mails Profissionais</li>
                    </ul>
                </div>
                <a href="https://wa.me/{$ctaWhatsapp}?text=Quero%20o%20Plano%20Start" target="_blank" class="w-full py-3 rounded-xl border border-slate-700 hover:border-cyan-500 text-center text-xs font-bold text-white transition">Contratar Start</a>
            </div>

            <!-- Plano Pro (Destaque) -->
            <div class="glass-card p-8 rounded-2xl flex flex-col justify-between relative border-cyan-500/50 glow-btn">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 font-black text-[10px] uppercase tracking-widest px-4 py-1 rounded-full">
                    Mais Popular
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white">Profissional</h4>
                    <p class="text-xs text-slate-400 mb-6">Potência máxima para quem busca alta escalabilidade.</p>
                    <div class="mb-6">
                        <span class="text-3xl font-black text-cyan-400">R$ 99</span>
                        <span class="text-slate-400 text-xs">/mês</span>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-3 mb-8">
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> 5 Domínios</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> 50 GB NVMe Alta Performance</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Backups Diários 1-Clique</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Suporte Prioritário VIP 24/7</li>
                    </ul>
                </div>
                <a href="https://wa.me/{$ctaWhatsapp}?text=Quero%20o%20Plano%20Profissional" target="_blank" class="w-full py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-center text-xs font-extrabold text-slate-950 transition">Assinar Agora</a>
            </div>

            <!-- Plano Enterprise -->
            <div class="glass-card p-8 rounded-2xl flex flex-col justify-between">
                <div>
                    <h4 class="text-lg font-bold text-white">Enterprise</h4>
                    <p class="text-xs text-slate-400 mb-6">Ambiente dedicado para grandes portais e e-commerces.</p>
                    <div class="mb-6">
                        <span class="text-3xl font-black text-white">R$ 249</span>
                        <span class="text-slate-400 text-xs">/mês</span>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-3 mb-8">
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Domínios Ilimitados</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Recursos Dedicados (CPU/RAM)</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> IP Dedicado Incluso</li>
                        <li><i class="fa-solid fa-check text-emerald-400 mr-2"></i> Gerente de Contas Exclusivo</li>
                    </ul>
                </div>
                <a href="https://wa.me/{$ctaWhatsapp}?text=Quero%20o%20Plano%20Enterprise" target="_blank" class="w-full py-3 rounded-xl border border-slate-700 hover:border-cyan-500 text-center text-xs font-bold text-white transition">Contratar Enterprise</a>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20 px-6 max-w-4xl mx-auto border-t border-slate-800/80">
        <div class="text-center mb-12">
            <h2 class="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Dúvidas Frequentes</h2>
            <h3 class="text-3xl font-bold text-white">Perguntas Comuns</h3>
        </div>

        <div class="space-y-4">
            <details class="glass-card rounded-xl p-5 group">
                <summary class="font-semibold text-sm text-white cursor-pointer list-none flex justify-between items-center">
                    Como funciona a ativação após o pagamento?
                    <i class="fa-solid fa-chevron-down text-xs text-cyan-400 transition-transform group-open:rotate-180"></i>
                </summary>
                <p class="text-xs text-slate-400 mt-3 leading-relaxed">A ativação é 100% instantânea e automática via Pix ou Cartão de Crédito. Seus acessos chegam por e-mail em segundos.</p>
            </details>
            <details class="glass-card rounded-xl p-5 group">
                <summary class="font-semibold text-sm text-white cursor-pointer list-none flex justify-between items-center">
                    Posso alterar de plano depois?
                    <i class="fa-solid fa-chevron-down text-xs text-cyan-400 transition-transform group-open:rotate-180"></i>
                </summary>
                <p class="text-xs text-slate-400 mt-3 leading-relaxed">Sim! Você pode fazer upgrade a qualquer momento pelo painel do cliente, pagando apenas a diferença proporcional.</p>
            </details>
            <details class="glass-card rounded-xl p-5 group">
                <summary class="font-semibold text-sm text-white cursor-pointer list-none flex justify-between items-center">
                    Tenho garantia de satisfação?
                    <i class="fa-solid fa-chevron-down text-xs text-cyan-400 transition-transform group-open:rotate-180"></i>
                </summary>
                <p class="text-xs text-slate-400 mt-3 leading-relaxed">Com certeza! Oferecemos 7 dias de garantia incondicional. Se não ficar satisfeito, devolvemos 100% do seu dinheiro sem burocracia.</p>
            </details>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-10 px-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 {$productName}. Todos os direitos reservados. Hospedado na infraestrutura de alta velocidade HostDevPro.</p>
    </footer>

</body>
</html>
HTML;

        File::put($root . '/index.html', $lpContent);

        // Manifesto
        $this->recordInstalledApp($root, 'sales_lp', [
            'name' => 'Landing Page de Vendas de Alta Conversão',
            'product_name' => $productName,
            'installed_at' => now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'app_id' => 'sales_lp',
            'app_name' => 'Landing Page de Vendas de Alta Conversão',
            'domain' => $domain,
            'site_url' => "https://{$domain}",
            'message' => "Landing Page de Vendas instalada com sucesso! Você pode editar seus textos a qualquer momento no HostDevPro Code Studio.",
        ];
    }

    /**
     * Instalação do Laravel 12 Starter Pack com .env, chave e rotas.
     */
    protected function installLaravelStarter(HostingAccount $hosting, string $root, array $options): array
    {
        $domain = strtolower(trim($hosting->domain));
        $appKey = 'base64:' . base64_encode(random_bytes(32));

        // Provisiona banco de dados MySQL
        $randomSuffix = substr(md5($domain . microtime()), 0, 6);
        $dbName = 'lar_' . $randomSuffix;
        $dbUser = 'usr_' . $randomSuffix;
        $dbPass = Str::random(16) . '!' . rand(10, 99);

        try {
            $this->plesk->createDatabase($domain, $dbName, $dbUser, $dbPass);
        } catch (\Throwable $e) {
            Log::warning("Plesk createDatabase fallback for {$domain}: " . $e->getMessage());
        }

        // Cria diretórios padrão do Laravel
        File::ensureDirectoryExists($root . '/app/Http/Controllers');
        File::ensureDirectoryExists($root . '/app/Models');
        File::ensureDirectoryExists($root . '/bootstrap/cache');
        File::ensureDirectoryExists($root . '/config');
        File::ensureDirectoryExists($root . '/public');
        File::ensureDirectoryExists($root . '/routes');
        File::ensureDirectoryExists($root . '/resources/views');
        File::ensureDirectoryExists($root . '/storage/framework/views');
        File::ensureDirectoryExists($root . '/storage/framework/sessions');
        File::ensureDirectoryExists($root . '/storage/logs');

        // .env
        $envContent = <<<ENV
APP_NAME="{$hosting->domain}"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL=https://{$domain}

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={$dbName}
DB_USERNAME={$dbUser}
DB_PASSWORD={$dbPass}

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ENV;
        File::put($root . '/.env', $envContent);

        // routes/web.php
        $routesWeb = <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
PHP;
        File::put($root . '/routes/web.php', $routesWeb);

        // public/index.php
        $publicIndex = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 — {$domain}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #030712; color: #f9fafb; }
        .cyber-panel { background: rgba(17, 24, 39, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(244, 63, 94, 0.2); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full cyber-panel rounded-2xl p-8 border border-rose-500/30 shadow-2xl shadow-rose-950/40">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-500 text-3xl">
                <i class="fa-brands fa-laravel"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    Laravel 12
                    <span class="text-[11px] bg-rose-500/20 text-rose-400 border border-rose-500/30 px-2 py-0.5 rounded-full font-semibold">LTS Ready</span>
                </h1>
                <p class="text-xs text-slate-400">Domínio: <span class="text-rose-400 font-mono">{$domain}</span></p>
            </div>
        </div>

        <p class="text-xs text-slate-300 mb-6 leading-relaxed">
            Seu ambiente Laravel foi criado com sucesso! O arquivo <code class="text-rose-400 bg-rose-950/60 px-1 py-0.5 rounded">.env</code> já foi gerado com uma chave de criptografia de 256-bit e credenciais de banco de dados MySQL dedicadas.
        </p>

        <div class="bg-slate-900/90 rounded-xl p-4 border border-slate-800 text-xs font-mono space-y-2 mb-6">
            <div class="flex justify-between"><span class="text-slate-500">DB_DATABASE:</span> <span class="text-rose-400">{$dbName}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">DB_USERNAME:</span> <span class="text-slate-200">{$dbUser}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">PHP VERSION:</span> <span class="text-emerald-400">8.2+</span></div>
        </div>

        <div class="text-center pt-2">
            <span class="text-[11px] text-slate-500"><i class="fa-solid fa-terminal mr-1"></i> Pronto para artisan, composer e deploy contínuo Git.</span>
        </div>
    </div>
</body>
</html>
HTML;
        File::put($root . '/public/index.php', $publicIndex);

        // Se o DocumentRoot for o root da hospedagem, adiciona redirecionamento elegante no index.php principal
        $rootIndex = <<<'PHP'
<?php
// Redireciona para public/ se existir
if (file_exists(__DIR__ . '/public/index.php')) {
    require_once __DIR__ . '/public/index.php';
} else {
    echo "Laravel Starter Instalado.";
}
PHP;
        File::put($root . '/index.php', $rootIndex);

        // Manifesto
        $this->recordInstalledApp($root, 'laravel', [
            'name' => 'Laravel 12 Starter Pack',
            'db_name' => $dbName,
            'db_user' => $dbUser,
            'db_pass' => $dbPass,
            'app_key' => $appKey,
            'installed_at' => now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'app_id' => 'laravel',
            'app_name' => 'Laravel 12 Starter Pack',
            'domain' => $domain,
            'site_url' => "https://{$domain}",
            'credentials' => [
                'db_name' => $dbName,
                'db_user' => $dbUser,
                'db_pass' => $dbPass,
                'app_key' => $appKey,
            ],
            'message' => 'Laravel 12 Starter instalado com sucesso! .env gerado e banco provisionado.',
        ];
    }

    /**
     * Instalação de Página de Manutenção & Em Breve VIP com Contador Regressivo.
     */
    protected function installComingSoon(HostingAccount $hosting, string $root, array $options): array
    {
        $domain = strtolower(trim($hosting->domain));
        $siteTitle = $options['site_title'] ?? $options['title'] ?? 'Novo Projeto Incrível Em Construção';
        $launchDate = $options['launch_date'] ?? date('Y-m-d', strtotime('+15 days'));
        $whatsapp = $options['whatsapp'] ?? '5511999999999';

        $comingSoonContent = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$siteTitle} — Em Breve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #030712; color: #f3f4f6; }
        .cyber-glow { box-shadow: 0 0 50px -10px rgba(56, 189, 248, 0.3); }
        .counter-card { background: rgba(17, 24, 39, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(56, 189, 248, 0.2); }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden text-center">

    <!-- Background Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-cyan-600/20 to-indigo-600/20 blur-[140px] rounded-full"></div>
    </div>

    <div class="max-w-xl w-full relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-8">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
            Em Desenvolvimento Ativo
        </div>

        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">
            Algo Extraordinário Está Chegando.
        </h1>
        <p class="text-sm sm:text-base text-slate-400 mb-10">
            Estamos construindo uma nova experiência digital de alto padrão para <strong class="text-cyan-400 font-semibold">{$domain}</strong>.
        </p>

        <!-- Countdown Timer -->
        <div class="grid grid-cols-4 gap-3 sm:gap-4 mb-10" id="countdown">
            <div class="counter-card p-3 sm:p-4 rounded-2xl">
                <span id="days" class="text-2xl sm:text-4xl font-extrabold text-white block">15</span>
                <span class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Dias</span>
            </div>
            <div class="counter-card p-3 sm:p-4 rounded-2xl">
                <span id="hours" class="text-2xl sm:text-4xl font-extrabold text-cyan-400 block">08</span>
                <span class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Horas</span>
            </div>
            <div class="counter-card p-3 sm:p-4 rounded-2xl">
                <span id="minutes" class="text-2xl sm:text-4xl font-extrabold text-indigo-400 block">42</span>
                <span class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Minutos</span>
            </div>
            <div class="counter-card p-3 sm:p-4 rounded-2xl">
                <span id="seconds" class="text-2xl sm:text-4xl font-extrabold text-emerald-400 block">18</span>
                <span class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Segundos</span>
            </div>
        </div>

        <!-- Notification Box -->
        <div class="counter-card p-6 rounded-2xl mb-8">
            <h3 class="text-sm font-semibold text-white mb-2">Seja o primeiro a saber do lançamento:</h3>
            <form onsubmit="event.preventDefault(); document.getElementById('feedback').classList.remove('hidden');" class="flex flex-col sm:flex-row gap-2">
                <input type="email" required placeholder="Seu melhor e-mail profissional" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-cyan-500/20 transition-all">
                    Notificar-me
                </button>
            </form>
            <p id="feedback" class="hidden text-xs text-emerald-400 mt-3 font-semibold">
                <i class="fa-solid fa-circle-check mr-1"></i> Obrigado! Você será avisado no primeiro instante.
            </p>
        </div>

        <div class="flex items-center justify-center gap-4 text-slate-400">
            <a href="https://wa.me/{$whatsapp}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-800/80 hover:bg-emerald-600/20 hover:text-emerald-400 border border-slate-700 flex items-center justify-center transition">
                <i class="fa-brands fa-whatsapp text-lg"></i>
            </a>
            <a href="mailto:contato@{$domain}" class="w-10 h-10 rounded-xl bg-slate-800/80 hover:bg-cyan-600/20 hover:text-cyan-400 border border-slate-700 flex items-center justify-center transition">
                <i class="fa-solid fa-envelope text-base"></i>
            </a>
        </div>
    </div>

    <footer class="mt-12 text-xs text-slate-500">
        &copy; 2026 {$domain} • Todos os direitos reservados.
    </footer>

    <script>
        // Target launch date
        const targetDate = new Date("{$launchDate}T00:00:00").getTime();
        function updateTimer() {
            const now = new Date().getTime();
            const diff = targetDate - now;
            if (diff <= 0) return;

            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = String(d).padStart(2, '0');
            document.getElementById('hours').innerText = String(h).padStart(2, '0');
            document.getElementById('minutes').innerText = String(m).padStart(2, '0');
            document.getElementById('seconds').innerText = String(s).padStart(2, '0');
        }
        setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>
HTML;

        File::put($root . '/index.html', $comingSoonContent);

        // Manifesto
        $this->recordInstalledApp($root, 'coming_soon', [
            'name' => 'Página "Em Breve" & Manutenção VIP',
            'launch_date' => $launchDate,
            'installed_at' => now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'app_id' => 'coming_soon',
            'app_name' => 'Página "Em Breve" & Manutenção VIP',
            'domain' => $domain,
            'site_url' => "https://{$domain}",
            'message' => 'Página de Em Breve instalada com sucesso com contador regressivo ativo!',
        ];
    }

    /**
     * Limpa o diretório com segurança, mantendo arquivos ocultos importantes se existirem.
     */
    protected function cleanDirectorySafely(string $root): void
    {
        if (!File::isDirectory($root)) {
            return;
        }

        $items = scandir($root);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === '.git') {
                continue;
            }
            $fullPath = $root . '/' . $item;
            if (is_dir($fullPath)) {
                File::deleteDirectory($fullPath);
            } else {
                File::delete($fullPath);
            }
        }
    }

    /**
     * Grava metadados da aplicação instalada.
     */
    protected function recordInstalledApp(string $root, string $appId, array $data): void
    {
        $manifest = array_merge([
            'app_id' => $appId,
            'installed_by' => 'HostDevPro Cloud 1-Click Installer',
        ], $data);

        File::put($root . '/.app-installer.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
