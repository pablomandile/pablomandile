<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Guarda y borra las imágenes y logos de los proyectos en el disco público.
 * La usan el panel de administración y la API, así las dos entradas guardan
 * los archivos en el mismo lugar y los borran con la misma regla.
 */
class ProjectImages
{
    /**
     * Guarda las imágenes subidas y devuelve sus rutas, en el mismo orden.
     *
     * @param  array<int, UploadedFile>  $files
     * @return array<int, string>
     */
    public static function store(array $files): array
    {
        return collect($files)
            ->map(fn (UploadedFile $file): string => $file->store('projects', 'public'))
            ->values()
            ->all();
    }

    public static function storeLogo(UploadedFile $file): string
    {
        return $file->store('projects/logos', 'public');
    }

    /**
     * Borra una imagen del storage. Las URLs externas (http...) no se tocan:
     * no son archivos nuestros.
     */
    public static function delete(?string $image): void
    {
        if ($image !== null && $image !== '' && ! str_starts_with($image, 'http')) {
            Storage::disk('public')->delete($image);
        }
    }

    /**
     * @param  iterable<int, string>  $images
     */
    public static function deleteMany(iterable $images): void
    {
        foreach ($images as $image) {
            self::delete($image);
        }
    }
}
