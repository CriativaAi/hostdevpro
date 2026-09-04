<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    /**
     * Exibe o onboarding de ativação ou o dashboard do afiliado.
     */
    public function index(): View
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;

        if ($affiliate) {
            $affiliate->load([
                'commissions' => function ($query) {
                    $query->with(['referredUser', 'invoice'])->latest()->take(20);
                },
                'withdrawals' => function ($query) {
                    $query->latest()->take(10);
                }
            ]);
        }

        return view('affiliates.index', compact('affiliate'));
    }

    /**
     * Ativa a conta de afiliado para o usuário autenticado.
     */
    public function activate(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->affiliate) {
            return redirect()->route('affiliates.index')
                ->with('info', 'Sua conta de afiliado já está ativa.');
        }

        $code = Affiliate::generateUniqueCode($user->name);

        $affiliate = Affiliate::create([
            'user_id' => $user->id,
            'referral_code' => $code,
            'commission_percentage' => 15.00,
            'cookie_duration_days' => 90,
            'status' => 'active',
            'activated_at' => now(),
        ]);

        return redirect()->route('affiliates.index')
            ->with('success', "Parabéns! Sua conta de afiliado foi ativada com sucesso. Seu código exclusivo é {$affiliate->referral_code}.");
    }

    /**
     * Atualiza a chave PIX para repasse das comissões.
     */
    public function updatePix(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;

        if (!$affiliate) {
            return redirect()->route('affiliates.index')->with('error', 'Conta de afiliado não localizada.');
        }

        $validated = $request->validate([
            'pix_key' => ['required', 'string', 'max:255'],
            'pix_key_type' => ['required', 'string', 'in:cpf,cnpj,email,phone,random'],
        ], [
            'pix_key.required' => 'Informe a sua chave PIX.',
            'pix_key_type.required' => 'Selecione o tipo de chave PIX.',
        ]);

        $affiliate->update($validated);

        return back()->with('success', 'Chave PIX atualizada com sucesso para recebimento das comissões!');
    }

    /**
     * Solicita o saque das comissões disponíveis via PIX.
     */
    public function withdraw(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;

        if (!$affiliate) {
            return redirect()->route('affiliates.index')->with('error', 'Conta de afiliado não localizada.');
        }

        if (empty($affiliate->pix_key)) {
            return back()->with('error', 'Cadastre sua chave PIX antes de solicitar o resgate.');
        }

        $minWithdrawalCents = 5000; // R$ 50,00 mínimo
        if ($affiliate->balance_cents < $minWithdrawalCents) {
            return back()->with('error', 'O valor mínimo para solicitação de saque PIX é de R$ 50,00.');
        }

        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:50'],
        ]);

        $requestedCents = !empty($validated['amount'])
            ? (int) round($validated['amount'] * 100)
            : $affiliate->balance_cents;

        if ($requestedCents > $affiliate->balance_cents) {
            return back()->with('error', 'O valor solicitado excede o saldo disponível.');
        }

        AffiliateWithdrawal::create([
            'affiliate_id' => $affiliate->id,
            'amount_cents' => $requestedCents,
            'pix_key' => $affiliate->pix_key,
            'pix_key_type' => $affiliate->pix_key_type ?? 'cpf',
            'status' => 'pending',
            'notes' => 'Solicitação automática via Painel do Afiliado.',
        ]);

        $affiliate->decrement('balance_cents', $requestedCents);
        $affiliate->increment('total_withdrawn_cents', $requestedCents);

        $formatted = 'R$ ' . number_format($requestedCents / 100, 2, ',', '.');
        return back()->with('success', "Saque de {$formatted} solicitado com sucesso! A transferência PIX será processada em até 24 horas úteis.");
    }
}
