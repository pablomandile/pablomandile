<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectImagesRequest;
use App\Http\Requests\Api\UpsertProjectRequest;
use App\Models\Project;
use App\Models\Technology;
use App\Support\ProjectImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        return response()->json(['project' => $this->present($this->find($slug))]);
    }

    /**
     * Crea el proyecto si no existe (siempre como borrador, salvo que se pida
     * otra cosa) o actualiza solo los campos que vinieron. Correrlo dos veces
     * con los mismos datos no duplica nada.
     */
    public function upsert(UpsertProjectRequest $request, string $slug): JsonResponse
    {
        $data = Arr::except($request->validated(), ['technologies']);
        $project = Project::firstWhere('slug', $slug);
        $created = $project === null;

        if ($created) {
            $project = Project::create([
                ...$data,
                'slug' => $slug,
                'images' => [],
                'is_published' => $data['is_published'] ?? false,
            ]);
        } else {
            $project->update($data);
        }

        $unknown = [];

        if ($request->has('technologies')) {
            [$ids, $unknown] = $this->resolveTechnologies($request->validated('technologies', []));
            $project->technologies()->sync($ids);
        }

        return response()->json([
            'project' => $this->present($project),
            'created' => $created,
            'unknown_technologies' => $unknown,
            'admin_url' => route('admin.projects.edit', $project),
        ], $created ? 201 : 200);
    }

    /**
     * `replace` reemplaza todas las imágenes (y borra las anteriores del
     * storage); `append` agrega al final. La primera imagen es la portada.
     */
    public function images(ProjectImagesRequest $request, string $slug): JsonResponse
    {
        $project = $this->find($slug);
        $current = $project->images ?? [];
        $files = $request->file('images', []);
        $kept = $request->validated('mode') === 'append' ? $current : [];

        if (count($kept) + count($files) > ProjectImagesRequest::MAX_IMAGES) {
            throw ValidationException::withMessages([
                'images' => 'Un proyecto puede tener hasta '.ProjectImagesRequest::MAX_IMAGES
                    .' imágenes (ya tiene '.count($kept).').',
            ]);
        }

        $stored = ProjectImages::store($files);

        if ($kept === []) {
            ProjectImages::deleteMany($current);
        }

        $project->update(['images' => [...$kept, ...$stored]]);

        return response()->json(['project' => $this->present($project)]);
    }

    public function logo(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
        ]);

        $project = $this->find($slug);
        $previous = $project->logo;

        $project->update(['logo' => ProjectImages::storeLogo($request->file('logo'))]);
        ProjectImages::delete($previous);

        return response()->json(['project' => $this->present($project)]);
    }

    private function find(string $slug): Project
    {
        return Project::where('slug', $slug)->firstOrFail();
    }

    /**
     * Separa los nombres en IDs conocidos y nombres que no existen. La
     * comparación no distingue mayúsculas ni espacios de los bordes.
     *
     * @param  array<int, string>  $names
     * @return array{0: array<int, int>, 1: array<int, string>}
     */
    private function resolveTechnologies(array $names): array
    {
        $byName = Technology::all(['id', 'name'])
            ->keyBy(fn (Technology $technology): string => mb_strtolower(trim($technology->name)));

        $ids = [];
        $unknown = [];

        foreach ($names as $name) {
            $technology = $byName->get(mb_strtolower(trim($name)));

            if ($technology) {
                $ids[] = $technology->id;
            } else {
                $unknown[] = $name;
            }
        }

        return [array_values(array_unique($ids)), array_values(array_unique($unknown))];
    }

    private function present(Project $project): array
    {
        $project->load('technologies:id,name');

        return [
            'id' => $project->id,
            'slug' => $project->slug,
            'title' => $project->title,
            'description' => $project->description,
            'demo_url' => $project->demo_url,
            'repo_url' => $project->repo_url,
            'is_published' => $project->is_published,
            'is_featured' => $project->is_featured,
            'sort_order' => $project->sort_order,
            'technologies' => $project->technologies->pluck('name')->all(),
            'image_urls' => $project->image_urls,
            'logo_url' => $project->logo_url,
        ];
    }
}
