<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CertificateController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/certificates/Index', [
            'certificates' => Certificate::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'image' => ['required', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        Certificate::create([
            'title' => $data['title'],
            'image' => $request->file('image')->store('certificates', 'public'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return to_route('admin.certificates.index');
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
        ]);

        $certificate->update($data);

        return to_route('admin.certificates.index');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        if ($certificate->image && ! str_starts_with($certificate->image, 'http')) {
            Storage::disk('public')->delete($certificate->image);
        }

        $certificate->delete();

        return to_route('admin.certificates.index');
    }
}
