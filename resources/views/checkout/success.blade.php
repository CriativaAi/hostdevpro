@extends('layouts.checkout')

@section('content')
<div class="min-h-screen bg-[#05080e] text-slate-100 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-2xl w-full space-y-6">

        <div class="p-6 sm:p-10 rounded-3xl bg-[#090d16] border border-slate-800 shadow-2xl space-y-8 text-center relative overflow-hidden">
            
            <!-- Confete / Ícone de Sucesso -->
            <div class="w-20 h-20 rounded-3xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono uppercase tracking-wider">
                    ● CONTA DE HOSPEDAGEM 100% OPERACIONAL
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white">
                    Parabéns! Sua Hospedagem <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Está Ativa</span>
                </h1>
                <p class="text-sm text-slate-400 max-w-md mx-auto">
                    O provisionamento no cluster de alta disponibilidade foi concluído com sucesso.
                </p>
            </div>

            <!-- Card de Destaque do E-mail -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-950/40 via-slate-900/80 to-slate-900/80 border border-emerald-500/30 text-left space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">✉️</span>
                    <div>
                        <h2 class="text-sm font-bold text-white">Verifique sua Caixa de Entrada</h2>
                        <p class="text-xs text-slate-300">
                            Enviamos um e-mail completo com detalhes em HTML contendo suas credenciais de <strong>FTP, Plesk Obsidian, senhas e configurações de Webmail</strong> para:
                        </p>
                    </div>
                </div>
                <div class="px-3.5 py-2.5 rounded-xl bg-black/50 border border-emerald-500/20 font-mono text-xs text-emerald-400 font-bold truncate">
                    {{ $invoice->client?->email ?? 'seu-email@dominio.com' }}
                </div>
            </div>

            <!-- Dados Rápidos de DNS para Apontamento -->
            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 text-left space-y-3">
                <h2 class="text-xs font-mono uppercase tracking-wider text-amber-400 flex items-center gap-2">
                    <span>⚡ Apontamento no Registro.br / Cloudflare</span>
                </h2>
                <p class="text-xs text-slate-400">
                    Altere os servidores DNS do seu domínio para os nameservers autoritativos abaixo:
                </p>
                <div class="p-3.5 rounded-xl bg-black/60 font-mono text-xs text-slate-200 space-y-1 border border-slate-800">
                    <div>Master 1: <strong class="text-white">ns1.valueserver.net</strong> (177.93.111.32)</div>
                    <div>Slave 2: &nbsp;<strong class="text-white">ns2.valueserver.net</strong> (187.45.181.114)</div>
                    <div>Slave 3: &nbsp;<strong class="text-white">ns3.valueserver.net</strong> (51.81.81.61)</div>
                    <div>Slave 4: &nbsp;<strong class="text-white">ns4.valueserver.net</strong> (51.222.29.124)</div>
                </div>
            </div>

            <!-- Botão CTA -->
            <div class="pt-2">
                <a href="{{ route('dashboard') }}" 
                   class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-black font-black text-sm uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2">
                    <span>Entrar no Painel do Cliente HostDevPro &rarr;</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
