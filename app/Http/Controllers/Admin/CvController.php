<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CvController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('admin/Cv', [
            'cv' => $this->currentCv(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $previous = Setting::get('cv')['path'] ?? null;

        $path = $request->file('cv')->store('cv', 'public');

        // Al subir uno nuevo se borra el anterior (si era un archivo almacenado).
        if ($previous && $previous !== $path && ! str_starts_with($previous, 'http')) {
            Storage::disk('public')->delete($previous);
        }

        Setting::put('cv', [
            'path' => $path,
            'original_name' => $request->file('cv')->getClientOriginalName(),
            'uploaded_at' => now()->toDateTimeString(),
        ]);

        return to_route('admin.cv.edit');
    }

    public function destroy(): RedirectResponse
    {
        $path = Setting::get('cv')['path'] ?? null;

        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }

        Setting::put('cv', null);

        return to_route('admin.cv.edit');
    }

    /**
     * Datos del CV actual para el panel, o null si no hay uno cargado.
     *
     * @return array{url: string, name: string, uploaded_at: ?string}|null
     */
    private function currentCv(): ?array
    {
        $cv = Setting::get('cv');
        $path = $cv['path'] ?? null;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return [
            'url' => Storage::disk('public')->url($path),
            'name' => $cv['original_name'] ?? basename($path),
            'uploaded_at' => $cv['uploaded_at'] ?? null,
        ];
    }
}
