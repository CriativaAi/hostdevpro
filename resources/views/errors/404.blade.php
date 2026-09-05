<x-guest-layout>
    <div class="min-h-[75vh] flex flex-col items-center justify-center text-center px-4 py-12">
        <div class="w-20 h-20 rounded-3xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-black text-3xl mb-6 shadow-xl shadow-cyan-500/10 animate-pulse">
            404
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-2">
            Página ou Recurso Não Localizado
        </h1>

        <p class="text-sm text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">
            O item que você tentou acessar não existe, foi concluído ou foi removido durante a nossa rotina de higienização da plataforma.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" 
               class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                Voltar ao Dashboard &rarr;
            </a>
            <a href="{{ route('tickets.index') }}" 
               class="px-6 py-3 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                Ver Meus Chamados
            </a>
        </div>
    </div>
</x-guest-layout>
