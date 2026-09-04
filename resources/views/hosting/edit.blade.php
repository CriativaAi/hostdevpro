<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Editar Hospedagem: {{ $hosting->domain }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Atualize os planos, cotas, versão do PHP e status da conta.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hosting.show', $hosting) }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    Ver Detalhes
                </a>
                <a href="{{ route('hosting.index') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-[#B99470]/25 shadow-xl">
                <form method="POST" action="{{ route('hosting.update', $hosting) }}">
                    @method('PUT')
                    @include('hosting._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
