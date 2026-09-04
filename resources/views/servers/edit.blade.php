<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Editar Servidor: {{ $server->name }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Atualize os dados de rede, especificações de hardware e notas da instância.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('servers.show', $server) }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    Ver Raio-X
                </a>
                <a href="{{ route('servers.index') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-[#B99470]/25 shadow-xl">
                <form method="POST" action="{{ route('servers.update', $server) }}">
                    @method('PUT')
                    @include('servers._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
