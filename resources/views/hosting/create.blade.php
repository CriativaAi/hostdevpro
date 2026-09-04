<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Provisionar Nova Hospedagem
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Vincule um domínio a um cliente e servidor VPS com cota de recursos e SSL.
                </p>
            </div>
            <a href="{{ route('hosting.index') }}" 
               class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-[#B99470]/25 shadow-xl">
                <form method="POST" action="{{ route('hosting.store') }}">
                    @include('hosting._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
