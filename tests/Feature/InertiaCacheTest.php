<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Tests\TestCase;

class InertiaCacheTest extends TestCase
{
    /**
     * El XHR de Inertia y la navegación comparten URL y devuelven cuerpos
     * distintos. Si el navegador puede guardar el JSON, al restaurar una
     * pestaña descartada lo muestra crudo en pantalla.
     */
    public function test_the_inertia_xhr_response_cannot_be_stored(): void
    {
        $response = $this->get('/login', [
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $this->inertiaVersion(),
        ]);

        $response->assertOk();
        $this->assertStringContainsString('application/json', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('X-Inertia', (string) $response->headers->get('Vary'));
    }

    /**
     * `no-store` en el documento HTML desactivaría el back/forward cache de
     * Chrome: cada "atrás" pasaría a ser una ida completa a la red.
     */
    public function test_the_html_document_stays_cacheable_to_keep_the_bfcache(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $this->assertStringContainsString('text/html', (string) $response->headers->get('Content-Type'));
        $this->assertStringNotContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    /**
     * La versión del asset: sin ella Inertia contesta 409 en vez de la página.
     */
    private function inertiaVersion(): string
    {
        return (string) app(HandleInertiaRequests::class)->version(request());
    }
}
