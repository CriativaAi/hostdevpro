<section>
    <header>
        <h2 class="text-lg font-black text-white tracking-tight">
            {{ __('Atualizar Senha') }}
        </h2>

        <p class="mt-1 text-xs text-slate-400">
            {{ __('Certifique-se de que sua conta esteja usando uma senha longa e segura para se manter protegido.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-bold text-xs uppercase tracking-wider text-slate-300 mb-1.5">
                {{ __('Senha Atual') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="mt-1 block w-full px-4 py-2.5 rounded-xl border border-white/15 bg-black/40 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition text-sm" 
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-400 text-xs" />
        </div>

        <div>
            <label for="update_password_password" class="block font-bold text-xs uppercase tracking-wider text-slate-300 mb-1.5">
                {{ __('Nova Senha') }}
            </label>
            <input id="update_password_password" name="password" type="password" 
                   class="mt-1 block w-full px-4 py-2.5 rounded-xl border border-white/15 bg-black/40 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition text-sm" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-400 text-xs" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-bold text-xs uppercase tracking-wider text-slate-300 mb-1.5">
                {{ __('Confirmar Nova Senha') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="mt-1 block w-full px-4 py-2.5 rounded-xl border border-white/15 bg-black/40 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition text-sm" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-400 text-xs" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition">
                {{ __('Salvar Alterações') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-400 flex items-center gap-1.5"
                >
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    {{ __('Senha atualizada com sucesso!') }}
                </p>
            @endif
        </div>
    </form>
</section>
