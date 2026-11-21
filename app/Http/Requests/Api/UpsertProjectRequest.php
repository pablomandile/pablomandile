<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Datos de un proyecto que llegan por la API. Al crear, título y descripción
 * son obligatorios; al actualizar, todo es opcional y solo se toca lo que vino.
 */
class UpsertProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $creating = ! Project::where('slug', $this->route('slug'))->exists();
        $presence = $creating ? 'required' : 'sometimes';

        return [
            'title' => [$presence, 'string', 'max:150'],
            'description' => [$presence, 'array'],
            'description.es' => ['required_with:description', 'string'],
            'description.en' => ['required_with:description', 'string'],
            'demo_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'repo_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'technologies' => ['sometimes', 'array', 'max:100'],
            'technologies.*' => ['string', 'max:100'],
        ];
    }
}
