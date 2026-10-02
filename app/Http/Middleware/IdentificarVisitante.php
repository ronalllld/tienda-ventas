<?php

namespace App\Http\Middleware;

use App\Models\Visita;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class IdentificarVisitante
{
    private const PATRON_BOTS = '/bot|crawler|spider|slurp|bingpreview|facebookexternalhit|curl|wget|python-requests|headlesschrome|ahrefs|semrush/i';

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->esBot($request)) {
            return $next($request);
        }

        $visitorId = $request->cookie('vid');
        $esNuevo = ! $visitorId || ! Str::isUuid($visitorId);

        if ($esNuevo) {
            $visitorId = (string) Str::uuid();
        }

        Visita::updateOrCreate(
            ['visitor_id' => $visitorId, 'fecha' => now()->toDateString()],
            ['ruta' => $request->path(), 'ultima_actividad' => now()]
        );

        $response = $next($request);

        if ($esNuevo) {
            $response->headers->setCookie(cookie('vid', $visitorId, 60 * 24 * 365, path: '/', sameSite: 'lax'));
        }

        return $response;
    }

    private function esBot(Request $request): bool
    {
        $agente = (string) $request->userAgent();

        if ($agente === '') {
            return true;
        }

        return (bool) preg_match(self::PATRON_BOTS, $agente);
    }
}
