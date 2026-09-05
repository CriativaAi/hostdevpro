<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                    Editar Hospedagem: {{ $hosting->domain }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Atualize os planos, cotas, versão do PHP e status da conta.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hosting.show', $hosting) }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    Ver Detalhes
                </a>
                <a href="{{ route('hosting.index') }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl p-6 sm:p-8 border border-white/15 shadow-2xl text-white">
                <form method="POST" action="{{ route('hosting.update', $hosting) }}">
                    @method('PUT')
                    @include('hosting._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
