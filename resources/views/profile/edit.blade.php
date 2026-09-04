<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                    {{ __('Perfil & Segurança') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Gerencie suas credenciais de acesso, e-mail e chave de autenticação.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                &larr; Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Card 1: Informações do Perfil -->
            <div class="p-6 sm:p-8 bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl rounded-2xl text-white">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card 2: Atualização de Senha -->
            <div class="p-6 sm:p-8 bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl rounded-2xl text-white">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card 3: Exclusão de Conta -->
            <div class="p-6 sm:p-8 bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl rounded-2xl text-white">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
