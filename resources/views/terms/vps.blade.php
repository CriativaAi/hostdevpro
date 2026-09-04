<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Servidores VPS e Nuvem Dedicada — HostDevPro</title>
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
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#5F6F52]/10 text-[#5F6F52] text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#5F6F52]"></span>
                    Documento Oficial de Infraestrutura
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#783D19] tracking-tight">
                    Contrato de Prestação de Serviços de Servidores VPS e Nuvem Dedicada
                </h1>
                <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                    Termos regulatórios para contratação, provisionamento, política de uso aceitável, suporte e níveis de serviço (SLA) para instâncias VPS e Cloud HostDevPro.
                </p>
                <p class="text-xs text-gray-400 mt-2 font-mono">
                    Última atualização: Setembro de 2026 • Versão 1.3
                </p>
            </div>

            <!-- Alerta Crítico de Suporte Unmanaged -->
            <div class="rounded-2xl bg-[#FEFAE0] border-l-4 border-[#C4661F] p-5 mb-8 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="p-1.5 bg-[#C4661F]/10 rounded-lg text-[#C4661F]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#783D19]">Aviso Importante sobre Gestão de Acesso ROOT</h4>
                        <p class="text-xs text-gray-700 mt-1 leading-relaxed">
                            Os planos de Servidores Virtuais (VPS) são fornecidos no modelo <strong>Não Gerenciado (Unmanaged)</strong>. O suporte padrão da HostDevPro abrange exclusivamente a infraestrutura de hardware, conectividade de rede e disponibilidade do painel. Erros causados pelo cliente — como instalações indevidas, alterações críticas no ROOT do SSH, perda de credenciais ou falhas de pacotes de terceiros — estão sujeitos a tarifação avulsa para intervenção e reparo.
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
                        O presente contrato tem por objeto a prestação de serviços de infraestrutura computacional virtualizada (Servidor Virtual Privado - VPS / Cloud Dedicado), compreendendo a alocação de vCPU, memória RAM, armazenamento NVMe de alta velocidade, tráfego de dados e endereço IPv4/IPv6 exclusivo, conforme o plano contratado pelo CLIENTE junto à <strong>HostDevPro</strong>.
                    </p>
                </section>

                <!-- Cláusula 2 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 2ª — Do Modelo de Serviço e Escopo de Suporte
                    </h3>
                    <p class="mt-2">
                        <strong>2.1. Natureza Unmanaged:</strong> O CLIENTE detém acesso privilegiado (root/sudo) e é o único responsável pela instalação, configuração, atualização, segurança, compilação de softwares, bancos de dados e código-fonte de suas aplicações.
                    </p>
                    <p class="mt-2">
                        <strong>2.2. Abrangência do Suporte Gratuito:</strong> O suporte técnico contínuo fornecido pela HostDevPro limita-se estritamente a:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Disponibilidade do nó físico (host machine), rede, energia e hipervisor;</li>
                        <li>Roteamento de IPs, conectividade de borda e proteção anti-DDoS básica;</li>
                        <li>Ferramentas do painel web para reiniciar, desligar e reinstalar o sistema operacional base.</li>
                    </ul>
                    <p class="mt-2">
                        <strong>2.3. Serviços Adicionais / Suporte Avançado:</strong> Caso o CLIENTE solicite intervenções como restauração de sistema operacional corrompido, auditoria de código, remoção de malwares internos ou reconfiguração de serviços após intervenções manuais malsucedidas no ROOT, tais atividades serão orçadas e faturadas separadamente mediante aprovação prévia.
                    </p>
                </section>

                <!-- Cláusula 3 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 3ª — Da Política de Uso de Recursos e Boas Práticas (Fair Use)
                    </h3>
                    <p class="mt-2">
                        <strong>3.1.</strong> É expressamente vedado o uso do VPS para:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Mineração de criptomoedas ou processos de estresse deliberado de CPU sem autorização prévia;</li>
                        <li>Disparo de e-mails em massa não solicitados (SPAM), sob pena de bloqueio imediato das portas 25/587 e rescisão motivada;</li>
                        <li>Ataques de negação de serviço (DoS/DDoS), escaneamento de portas, phishing ou hospedagem de material ilícito.</li>
                    </ul>
                    <p class="mt-2">
                        <strong>3.2.</strong> Em casos de uso abusivo crônico que comprometa a estabilidade dos demais nós do cluster, a HostDevPro reserva-se o direito de isolar ou aplicar throttle na instância até a resolução pelo CLIENTE.
                    </p>
                </section>

                <!-- Cláusula 4 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 4ª — Dos Níveis de Serviço (SLA) e Disponibilidade
                    </h3>
                    <p class="mt-2">
                        A HostDevPro compromete-se a manter uma disponibilidade de rede de <strong>99,8%</strong> ao mês, excetuando-se manutenções preventivas programadas (notificadas com antecedência mínima de 24 horas) e eventos de força maior ou falhas em operadoras de telecomunicação externas.
                    </p>
                </section>

                <!-- Cláusula 5 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 5ª — Dos Backups e Salvaguarda de Dados
                    </h3>
                    <p class="mt-2">
                        A responsabilidade primária pela cópia de segurança (backup) de todos os arquivos, bases de dados e configurações do servidor é integralmente do CLIENTE. A HostDevPro oferece rotinas de snapshot de contingência de infraestrutura, mas não se responsabiliza por perdas de dados decorrentes de comandos executados pelo usuário, exclusões acidentais ou invasões no nível da aplicação.
                    </p>
                </section>

                <!-- Cláusula 6 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 6ª — Do Pagamento, Suspensão e Cancelamento
                    </h3>
                    <p class="mt-2">
                        O serviço é pré-pago. O atraso no pagamento por prazo superior a 5 (cinco) dias corridos ensejará a suspensão automática da instância. Permanecendo a inadimplência por mais de 15 (quinze) dias corridos, o servidor e todos os dados nele armazenados serão destruídos definitivamente para desalocação de recursos físicos.
                    </p>
                </section>

                <!-- Cláusula 7 -->
                <section>
                    <h3 class="text-lg font-bold text-[#783D19] border-b border-gray-100 pb-2">
                        Cláusula 7ª — Do Foro
                    </h3>
                    <p class="mt-2">
                        As partes elegem o Foro da Comarca da sede da CONTRATADA para dirimir quaisquer dúvidas decorrentes do presente contrato, com renúncia expressa a qualquer outro.
                    </p>
                </section>
            </div>

            <!-- Rodapé interno do Documento -->
            <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500">
                    HostDevPro Tecnologia e Hospedagem Cloud • Documento registrado
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('terms.hosting') }}" class="text-xs text-[#C4661F] font-bold hover:underline">
                        Ver Contrato de Hospedagem &rarr;
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Rodapé Global -->
    @include('partials.footer')
</body>
</html>
