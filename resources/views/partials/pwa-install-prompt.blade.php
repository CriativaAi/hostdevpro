<!-- Banner de Instalação PWA Inteligente (Mobile / Celular) -->
<div x-data="{
    deferredPrompt: null,
    showBanner: false,
    isIos: false,
    showIosTip: false,
    init() {
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        const dismissed = localStorage.getItem('hdp_pwa_dismissed');
        const isDismissed = dismissed && (Date.now() - parseInt(dismissed) < 7 * 24 * 60 * 60 * 1000);

        if (isStandalone || isDismissed) {
            return;
        }

        // Detectar iOS Safari
        const userAgent = window.navigator.userAgent.toLowerCase();
        this.isIos = /iphone|ipad|ipod/.test(userAgent) && !window.MSStream;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showBanner = true;
        });

        // No iOS, se for mobile e não estiver em standalone, mostrar após 3 segundos
        if (this.isIos && !isStandalone) {
            setTimeout(() => {
                this.showBanner = true;
                this.showIosTip = true;
            }, 3500);
        }
    },
    async installPwa() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                this.showBanner = false;
            }
            this.deferredPrompt = null;
        }
    },
    dismiss() {
        this.showBanner = false;
        localStorage.setItem('hdp_pwa_dismissed', Date.now().toString());
    }
}" x-cloak>
    <div x-show="showBanner" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-4 inset-x-4 sm:left-auto sm:right-6 sm:max-w-md z-50">
        
        <div class="bg-slate-900/95 backdrop-blur-md border border-slate-750 p-4 sm:p-5 rounded-3xl shadow-2xl text-white flex items-start gap-3.5 relative overflow-hidden">
            <!-- Glow sutil -->
            <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-emerald-500/15 rounded-full blur-xl pointer-events-none"></div>

            <!-- Ícone HDP -->
            <img src="{{ asset('brand/icons/HDP-icon-128.webp') }}" 
                 alt="HostDevPro App" 
                 class="w-12 h-12 rounded-2xl shadow-md border border-slate-700 flex-shrink-0">

            <div class="flex-1 pr-6 space-y-1">
                <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider block">
                    Aplicativo Web (PWA)
                </span>
                <h4 class="text-xs font-extrabold text-white leading-tight">
                    Instalar HostDevPro no Celular
                </h4>
                <p class="text-[11px] text-slate-300 leading-relaxed font-sans">
                    Acesso instantâneo a faturas, chamados e servidores sem ocupar memória.
                </p>

                <template x-if="!showIosTip">
                    <div class="pt-2">
                        <button type="button" 
                                @click="installPwa()"
                                class="px-4 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow transition">
                            Instalar App
                        </button>
                    </div>
                </template>

                <template x-if="showIosTip">
                    <div class="pt-1.5 text-[11px] text-emerald-300 font-sans">
                        Toque em <span class="font-bold">Compartilhar</span> (⎙) e selecione <span class="font-bold">"Adicionar à Tela de Início"</span> (+).
                    </div>
                </template>
            </div>

            <!-- Botão Fechar -->
            <button type="button" 
                    @click="dismiss()"
                    class="absolute top-3.5 right-3.5 text-slate-400 hover:text-white p-1 rounded-lg transition"
                    title="Fechar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
</div>

<!-- Registro do Service Worker -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('PWA Service Worker ativo:', reg.scope))
                .catch(err => console.log('Falha ao registrar Service Worker:', err));
        });
    }
</script>
