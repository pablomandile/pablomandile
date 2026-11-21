<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protege la API del portfolio con un único token fijo (PORTFOLIO_API_TOKEN).
 * El único cliente es el agente que sube proyectos, así que no hace falta más.
 * Sin token configurado, la API queda apagada: responde 401 a todo.
 */
class EnsurePortfolioApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.portfolio_api.token');
        $given = (string) $request->bearerToken();

        if ($expected === '' || $given === '' || ! hash_equals($expected, $given)) {
            return $this->noStore(response()->json(['message' => 'No autorizado.'], 401));
        }

        return $this->noStore($next($request));
    }

    /**
     * El CDN de Hostinger cachea agresivo: ninguna respuesta de la API se
     * guarda en ningún lado (mismo criterio que HandleInertiaRequests).
     */
    private function noStore(Response $response): Response
    {
        $response->headers->set('Cache-Control', 'no-store, private');

        return $response;
    }
}
