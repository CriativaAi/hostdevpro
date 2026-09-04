<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Abrir Novo Chamado de Suporte
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Inicie uma nova solicitação técnica, financeira ou de infraestrutura vinculada a um cliente.
                </p>
            </div>
            <a href="{{ route('tickets.index') }}" 
               class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                &larr; Voltar para Chamados
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-[#B99470]/25 shadow-xl">
                <form method="POST" action="{{ route('tickets.store') }}">
                    @include('tickets._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
