<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TechCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechnologyRequest;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TechnologyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/technologies/Index', [
            'technologies' => Technology::query()
                ->orderBy('category')
                ->orderBy('sort_order')
                ->get(['id', 'name', 'category', 'icon', 'sort_order']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/technologies/Create', [
            'categories' => $this->categories(),
        ]);
    }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        Technology::create($request->validated());

        return to_route('admin.technologies.index');
    }

    public function edit(Technology $technology): Response
    {
        return Inertia::render('admin/technologies/Edit', [
            'technology' => $technology,
            'categories' => $this->categories(),
        ]);
    }

    public function update(TechnologyRequest $request, Technology $technology): RedirectResponse
    {
        $technology->update($request->validated());

        return to_route('admin.technologies.index');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $technology->delete();

        return to_route('admin.technologies.index');
    }

    /**
     * Opciones de categoría para los selects del formulario.
     */
    private function categories(): array
    {
        return collect(TechCategory::cases())
            ->map(fn (TechCategory $category) => [
                'value' => $category->value,
                'label' => ucfirst($category->value),
            ])
            ->all();
    }
}
