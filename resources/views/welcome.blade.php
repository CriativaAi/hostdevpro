<x-guest-layout>
    <div class="text-center py-4">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            Painel Corporativo <span class="text-indigo-600">HostDevPro</span>
        </h1>
        <p class="text-sm text-gray-500 mt-2">
            Plataforma centralizada para gestão de clientes, projetos, instâncias VPS e infraestrutura cloud.
        </p>

        <div class="mt-8 flex flex-col gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 shadow-sm transition">
                    Acessar Meu Dashboard &rarr;
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 shadow-sm transition">
                    Entrar na Plataforma
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50 transition">
                        Criar Nova Conta
                    </a>
                @endif
            @endauth
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-gray-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Ambiente Seguro & Conexão Criptografada SSL</span>
        </div>
    </div>
</x-guest-layout>
