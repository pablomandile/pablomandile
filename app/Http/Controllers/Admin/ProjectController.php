<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/projects/Index', [
            'projects' => Project::with('technologies:id,name')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/projects/Create', [
            'technologies' => $this->technologyOptions(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request->validated());

        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')->store('projects', 'public');
        } else {
            unset($data['preview_image']);
        }

        $project = Project::create($data);
        $project->technologies()->sync($request->validated('technologies', []));

        return to_route('admin.projects.index');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('admin/projects/Edit', [
            'project' => $project->load('technologies:id,name'),
            'technologies' => $this->technologyOptions(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $this->prepareData($request->validated());

        if ($request->hasFile('preview_image')) {
            $this->deleteStoredImage($project);
            $data['preview_image'] = $request->file('preview_image')->store('projects', 'public');
        } else {
            unset($data['preview_image']);
        }

        $project->update($data);
        $project->technologies()->sync($request->validated('technologies', []));

        return to_route('admin.projects.index');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteStoredImage($project);
        $project->delete();

        return to_route('admin.projects.index');
    }

    private function prepareData(array $data): array
    {
        unset($data['technologies']);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        return $data;
    }

    private function technologyOptions()
    {
        return Technology::orderBy('category')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'category']);
    }

    private function deleteStoredImage(Project $project): void
    {
        if ($project->preview_image && ! str_starts_with($project->preview_image, 'http')) {
            Storage::disk('public')->delete($project->preview_image);
        }
    }
}
