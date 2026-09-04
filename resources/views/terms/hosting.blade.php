<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Hospedagem de Sites e Aplicações — HostDevPro</title>
    <link rel="icon" type="image/webp" href="{{ asset('brand/icons/HDP-icon-64.webp') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-hostdev-cloud text-gray-100 min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white relative overflow-x-hidden">
    <!-- Elementos decorativos de iluminação (Orbs) -->
    <div class="fixed top-20 left-10 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>
    <div class="fixed bottom-10 right-20 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

    <!-- Fundo de Código Easter Egg -->
    @include('partials.code-background')

    <!-- Barra de Navegação Superior do Contrato -->
    <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-30 shadow-lg">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}" alt="HostDevPro" class="h-8 w-auto">
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-900/60 text-xs font-semibold text-slate-300 hover:bg-slate-800 transition print:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimir / PDF</span>
                </button>
                <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold uppercase tracking-wider hover:bg-blue-500 shadow-sm transition">
                    Portal do Cliente
                </a>
            </div>
        </div>
    </header>

    <!-- Conteúdo do Contrato -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl border border-[#B99470]/25 p-8 sm:p-12">
            
            <!-- Cabeçalho do Documento -->
            <div class="border-b border-gray-200 pb-8 mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#A9B388]/20 text-[#5F6F52] text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#5F6F52]"></span>
                    Documento Oficial de Serviços Web
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#783D19] tracking-tight">
                    Contrato de Prestação de Serviços de Hospedagem de Sites e Aplicações
                </h1>
                <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                    Termos e condições gerais para hospedagem de sites, lojas virtuais, plataformas SaaS, bancos de dados, contas de e-mail corporativo e gestão de domínios na HostDevPro.
                </p>
                <p class="text-xs text-gray-400 mt-2 font-mono">
                    Última atualização: Setembro de 2026 • Versão 1.3
                </p>
            </div>

            <!-- Destaque: Ambiente Gerenciado -->
            <div class="rounded-2xl bg-[#FEFAE0] border-l-4 border-[#5F6F52] p-5 mb-8 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="p-1.5 bg-[#5F6F52]/10 rounded-lg text-[#5F6F52]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#783D19]">Ambiente Gerenciado de Alto Desempenho</h4>
                        <p class="text-xs text-gray-700 mt-1 leading-relaxed">
                            O serviço de hospedagem HostDevPro inclui servidores web otimizados (OpenResty/Nginx), suporte às versões mais recentes do PHP e Node.js, certificados SSL gratuitos e automatizados Let's Encrypt e proteção ativa contra ataques cibernéticos.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Corpo das Cláusulas -->
            <div class="prose prose-slate max-w-none text-sm leading-relaxed text-gray-700 space-y-8">
                
                <!-- Cláusula 1 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 1ª — Do Objeto
                    </h3>
                    <p class="mt-2">
                        O presente instrumento regula a disponibilização e manutenção de espaço em servidor compartilhado ou semi-dedicado gerenciado pela <strong>HostDevPro</strong>, destinado ao armazenamento e veiculação de websites, aplicações web, bancos de dados relacionais e caixas postais eletrônicas do CLIENTE.
                    </p>
                </section>

                <!-- Cláusula 2 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 2ª — Da Política Anti-Spam e Boas Práticas de E-mail
                    </h3>
                    <p class="mt-2">
                        <strong>2.1. Política de Tolerância Zero ao SPAM:</strong> É expressamente proibido o envio de e-mails em massa não solicitados (SPAM), envio para listas compradas ou raspadas, e qualquer prática que degrade a reputação dos IPs da CONTRATADA.
                    </p>
                    <p class="mt-2">
                        <strong>2.2. Limites de Envio:</strong> Para garantir a entregabilidade de todos os clientes no ambiente compartilhado, aplica-se o limite máximo de disparos por hora estipulado no plano contratado. Tentativas de ultrapassar o limite resultarão em enfileiramento automático ou bloqueio temporário do remetente.
                    </p>
                </section>

                <!-- Cláusula 3 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 3ª — Do Uso de Recursos (CPU, Memória e Inodes)
                    </h3>
                    <p class="mt-2">
                        <strong>3.1.</strong> O CLIENTE concorda em fazer uso regular e razoável dos recursos computacionais. Scripts com loops infinitos, consultas SQL não indexadas de alta sobrecarga ou processos que consumam continuamente mais de 25% do processamento do nó compartilhado serão terminados automaticamente pelos sistemas de proteção.
                    </p>
                    <p class="mt-2">
                        <strong>3.2.</strong> O espaço em disco fornecido destina-se exclusivamente ao funcionamento da aplicação web. Não é permitido utilizar a hospedagem como repositório de arquivos pessoais, backups externos ou compartilhamento de mídias piratas.
                    </p>
                </section>

                <!-- Cláusula 4 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 4ª — Dos Backups e Rotinas de Segurança
                    </h3>
                    <p class="mt-2">
                        A HostDevPro realiza rotinas automatizadas de backup periódico do ambiente para recuperação em caso de desastres físicos. Contudo, recomenda-se expressamente que o CLIENTE mantenha cópias de segurança locais e atualizadas de seus arquivos e bancos de dados através dos atalhos de exportação disponíveis no painel.
                    </p>
                </section>

                <!-- Cláusula 5 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 5ª — Do Suporte Técnico e Atendimento
                    </h3>
                    <p class="mt-2">
                        O suporte técnico é prestado através da abertura de <strong>Chamados (Tickets)</strong> no Portal do Cliente e pelo canal de atendimento corporativo oficial. O suporte inclui assistência a problemas no servidor web, rotas DNS, configurações de PHP, falhas de envio de e-mails do servidor e restauração de backups gerenciados. Não abrange desenvolvimento, refatoração de código do cliente ou personalização de temas de terceiros.
                    </p>
                </section>

                <!-- Cláusula 6 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 6ª — Do Pagamento, Renovação e Suspensão
                    </h3>
                    <p class="mt-2">
                        Os pagamentos são processados em ciclos mensais, trimestrais ou anuais conforme a periodicidade contratada. O não pagamento até a data do vencimento acarreta a suspensão dos serviços após 5 (cinco) dias de atraso, e o cancelamento definitivo com exclusão dos dados após 20 (vinte) dias.
                    </p>
                </section>

                <!-- Cláusula 7 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 7ª — Da Conformidade LGPD
                    </h3>
                    <p class="mt-2">
                        A HostDevPro atua como Operadora de Dados conforme a Lei Geral de Proteção de Dados (Lei nº 13.709/2018), adotando medidas de segurança técnicas e administrativas aptas a proteger os dados armazenados contra acessos não autorizados.
                    </p>
                </section>

                <!-- Cláusula 8 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 8ª — Do Foro
                    </h3>
                    <p class="mt-2">
                        As partes elegem o Foro da Comarca da sede da HostDevPro para dirimir eventuais controvérsias decorrentes deste contrato, com exclusão de qualquer outro.
                    </p>
                </section>
            </div>

            <!-- Rodapé interno do Documento -->
            <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500">
                    HostDevPro Tecnologia e Hospedagem Cloud • Documento registrado
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('terms.vps') }}" class="text-xs text-[#C4661F] font-bold hover:underline">
                        Ver Contrato de VPS &rarr;
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Rodapé Global -->
    @include('partials.footer')
</body>
</html>
