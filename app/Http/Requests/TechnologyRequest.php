<?php

namespace App\Http\Requests;

use App\Enums\TechCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('technologies', 'name')->ignore($this->route('technology')),
            ],
            'category' => ['required', new Enum(TechCategory::class)],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['integer', 'min:0', 'max:65535'],
        ];
    }
}
