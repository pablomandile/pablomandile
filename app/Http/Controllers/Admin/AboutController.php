<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('admin/About', [
            'about' => Setting::get('about', ['es' => '', 'en' => '']),
            'experiences' => Experience::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id', 'role', 'company', 'period']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'about.es' => ['required', 'string'],
            'about.en' => ['required', 'string'],
            'experiences' => ['array'],
            'experiences.*.role.es' => ['required', 'string', 'max:150'],
            'experiences.*.role.en' => ['required', 'string', 'max:150'],
            'experiences.*.company' => ['required', 'string', 'max:150'],
            'experiences.*.period.es' => ['required', 'string', 'max:100'],
            'experiences.*.period.en' => ['required', 'string', 'max:100'],
        ]);

        Setting::put('about', [
            'es' => $data['about']['es'],
            'en' => $data['about']['en'],
        ]);

        DB::transaction(function () use ($data) {
            Experience::query()->delete();

            foreach ($data['experiences'] ?? [] as $index => $experience) {
                Experience::create([
                    'role' => $experience['role'],
                    'company' => $experience['company'],
                    'period' => $experience['period'],
                    'sort_order' => $index + 1,
                ]);
            }
        });

        return to_route('admin.about.edit');
    }
}
