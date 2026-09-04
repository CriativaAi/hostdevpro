<x-guest-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 text-slate-300 font-sans leading-relaxed text-sm">
            
            <!-- Cabeçalho Legal -->
            <div class="border-b border-slate-800/80 pb-6">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Documento Jurídico &bull; LGPD Lei 13.709/2018</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Política de Privacidade & Proteção de Dados
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    HostDevPro Cloud Technology &bull; Atualizado em 04 de Setembro de 2026
                </p>
            </div>

            <!-- Cláusula 1 -->
            <div class="space-y-2">
                <h2 class="text-base font-bold text-white">1. Declaração Geral de Compromisso</h2>
                <p>
                    A <strong>HostDevPro</strong>, operada e desenvolvida em parceria com o ecossistema tecnológico <strong>CreativaAi Hub Technology</strong>, compromete-se com a segurança, integridade, confidencialidade e sigilo total dos dados de todos os seus clientes, parceiros e usuários. Esta Política atende integralmente à legislação brasileira de proteção de dados (Lei nº 13.709/2018 - LGPD).
                </p>
            </div>

            <!-- Cláusula 2 -->
            <div class="space-y-2">
                <h2 class="text-base font-bold text-white">2. Dados Pessoais Coletados e Finalidade</h2>
                <p>
                    Coletamos e processamos estritamente os dados necessários para o fornecimento, autenticação e suporte de infraestrutura cloud:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-slate-400 text-xs">
                    <li><strong>Dados Cadastrais:</strong> Nome completo, razão social, endereço de e-mail e número de telefone para contato técnico e emissão fiscal.</li>
                    <li><strong>Dados de Faturamento:</strong> Histórico de faturas, identificadores de transações via Mercado Pago (PIX) e Stripe. <em>Nenhum dado sensível de cartão de crédito é armazenado em nossos servidores locais</em>.</li>
                    <li><strong>Dados de Conexão e Logs:</strong> Endereços IP, carimbos de data/hora de acesso e registros de autenticação com a finalidade de auditoria e segurança contra ataques cibernéticos.</li>
                </ul>
            </div>

            <!-- Cláusula 3 -->
            <div class="space-y-2">
                <h2 class="text-base font-bold text-white">3. Armazenamento, Backups e Segurança Criptográfica</h2>
                <p>
                    Toda a infraestrutura da HostDevPro está alocada em datacenters de padrão Tier III com isolamento de nós Docker, discos de estado sólido NVMe corporativos e criptografia em trânsito por meio de protocolos TLS 1.3 com certificados Let's Encrypt (curvas elípticas EC-256).
                </p>
            </div>

            <!-- Cláusula 4 -->
            <div class="space-y-2">
                <h2 class="text-base font-bold text-white">4. Direitos do Titular dos Dados (Artigo 18 da LGPD)</h2>
                <p>
                    O usuário tem direito a solicitar, a qualquer momento via Central de Chamados ou pelo e-mail institucional do Encarregado de Dados (DPO): confirmação de tratamento, acesso imediato, retificação ou exclusão de dados desnecessários, observadas as obrigações fiscais de guarda.
                </p>
            </div>

            <!-- Rodapé do Documento -->
            <div class="pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <span class="text-slate-500">HostDevPro &bull; CreativaAi Hub Technology</span>
                <a href="{{ route('dashboard') }}" class="font-bold text-emerald-400 hover:text-emerald-300 transition">
                    &larr; Voltar para a Área do Cliente
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
