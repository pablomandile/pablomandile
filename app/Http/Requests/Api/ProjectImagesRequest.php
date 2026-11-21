<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProjectImagesRequest extends FormRequest
{
    /** Tope de imágenes por proyecto (el mismo que el panel). */
    public const MAX_IMAGES = 12;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mode' => ['required', 'in:replace,append'],
            'images' => ['required', 'array', 'min:1', 'max:'.self::MAX_IMAGES],
            'images.*' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ];
    }
}
