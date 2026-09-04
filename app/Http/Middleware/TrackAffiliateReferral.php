<?php

namespace App\Http\Middleware;

use App\Models\Affiliate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAffiliateReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ref = $request->query('ref') ?? $request->query('aff');

        if ($ref && is_string($ref)) {
            $ref = trim($ref);
            $affiliate = Affiliate::where('referral_code', $ref)
                ->where('status', 'active')
                ->first();

            if ($affiliate) {
                // Incrementa visitantes únicos somente se ainda não houver o cookie para esse afiliado
                if ($request->cookie('hdp_affiliate') !== $ref) {
                    $affiliate->increment('visitors_count');
                }

                // Define cookie seguro com validade de 90 dias (em minutos: 60 * 24 * 90)
                $durationMinutes = 60 * 24 * $affiliate->cookie_duration_days;
                cookie()->queue(cookie('hdp_affiliate', $ref, $durationMinutes));
            }
        }

        return $next($request);
    }
}
