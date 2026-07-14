<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:160', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($this->route('project'))],
            'description' => ['required', 'array'],
            'description.es' => ['required', 'string'],
            'description.en' => ['required', 'string'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'preview_image' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['integer', 'min:0', 'max:65535'],
            'technologies' => ['array'],
            'technologies.*' => ['integer', 'exists:technologies,id'],
        ];
    }
}
