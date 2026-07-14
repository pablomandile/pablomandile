<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Technology;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $profile = config('profile');
        $profile['photo_url'] = ! empty($profile['photo'])
            ? Storage::disk('public')->url($profile['photo'])
            : null;
        $profile['about'] = Setting::get('about', ['es' => '', 'en' => '']);
        $profile['experience'] = Experience::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['role', 'company', 'period']);

        return Inertia::render('Home', [
            'profile' => $profile,
            'technologies' => Technology::query()
                ->orderBy('sort_order')
                ->get(['id', 'name', 'category', 'icon'])
                ->groupBy(fn (Technology $technology) => $technology->category->value),
            'projects' => Project::query()
                ->where('is_published', true)
                ->with('technologies:id,name')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
