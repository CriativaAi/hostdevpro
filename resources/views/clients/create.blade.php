<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Clientes
                </a>
                <span class="text-slate-600">/</span>
                <h2 class="font-black text-xl text-white leading-tight">
                    {{ __('Novo Cliente') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl p-6 sm:p-8 border border-white/15 shadow-2xl text-white">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h3 class="text-lg font-black text-white">Informações Cadastrais</h3>
                    <p class="mt-1 text-xs text-slate-400">Cadastre um novo cliente ou empresa para gerenciamento de serviços e projetos no HostDevPro Cloud.</p>
                </div>

                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    @include('clients._form')

                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-end gap-3">
                        <a href="{{ route('clients.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-500/20">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
