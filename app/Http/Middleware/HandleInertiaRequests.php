<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Inertia\Support\Header;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Una misma URL de Inertia devuelve HTML para una navegación y JSON para un
     * XHR; lo único que las distingue para una caché es el `Vary: X-Inertia`.
     * El CDN de Hostinger lo reescribe (y con brotli lo borra), así que el
     * navegador guarda las dos bajo la misma clave: al restaurar una pestaña
     * descartada —una navegación de historial, que no revalida— reusa el JSON
     * y lo muestra crudo en pantalla.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = parent::handle($request, $next);

        // El CDN lo borra, pero se declara igual: es lo correcto y lo respeta
        // cualquier otro intermediario.
        $response->headers->set('Vary', Header::INERTIA.', Accept-Encoding');

        // `no-store` y no `no-cache`: `no-cache` permite guardar y solo obliga a
        // revalidar, y la navegación de historial saltea la revalidación.
        //
        // Solo sobre la respuesta XHR, nunca sobre el HTML: `no-store` en el
        // documento principal desactiva el back/forward cache de Chrome y
        // convierte cada "atrás" en una ida completa a la red.
        if ($request->header(Header::INERTIA)) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return array_merge(parent::share($request), [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
        ]);
    }
}
