<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Technology;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home', [
            'profile' => config('profile'),
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
