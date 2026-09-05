<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                    Cadastrar Novo Servidor
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Adicione uma nova instância VPS, nó Docker ou servidor dedicado ao inventário.
                </p>
            </div>
            <a href="{{ route('servers.index') }}" 
               class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl p-6 sm:p-8 border border-white/15 shadow-2xl text-white">
                <form method="POST" action="{{ route('servers.store') }}">
                    @include('servers._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
