<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
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
        $data['images'] = $this->storeUploadedImages($request);

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

        $current = $project->images ?? [];

        // Imágenes conservadas, en el orden que envió el admin (así puede
        // reordenar y elegir la portada), validadas contra las que existen.
        $kept = array_values(array_intersect($request->validated('existing_images', []), $current));

        // Borra del storage las imágenes que se quitaron.
        foreach (array_diff($current, $kept) as $removed) {
            $this->deleteStoredImage($removed);
        }

        // Las nuevas subidas se agregan al final de la lista.
        $data['images'] = array_values([...$kept, ...$this->storeUploadedImages($request)]);

        $project->update($data);
        $project->technologies()->sync($request->validated('technologies', []));

        return to_route('admin.projects.index');
    }

    public function destroy(Project $project): RedirectResponse
    {
        foreach ($project->images ?? [] as $image) {
            $this->deleteStoredImage($image);
        }

        $project->delete();

        return to_route('admin.projects.index');
    }

    private function prepareData(array $data): array
    {
        unset($data['technologies'], $data['new_images'], $data['existing_images']);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        return $data;
    }

    /**
     * Guarda los archivos subidos en `new_images` y devuelve sus rutas.
     *
     * @return array<int, string>
     */
    private function storeUploadedImages(Request $request): array
    {
        return collect($request->file('new_images', []))
            ->map(fn (UploadedFile $file): string => $file->store('projects', 'public'))
            ->all();
    }

    private function technologyOptions()
    {
        return Technology::orderBy('category')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'category']);
    }

    private function deleteStoredImage(string $image): void
    {
        if ($image !== '' && ! str_starts_with($image, 'http')) {
            Storage::disk('public')->delete($image);
        }
    }
}
