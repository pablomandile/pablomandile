<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'demo_url',
        'repo_url',
        'images',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $appends = ['image_urls', 'cover_url'];

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'images' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * URLs resueltas de todas las imágenes, en orden (la primera es la portada).
     */
    protected function imageUrls(): Attribute
    {
        return Attribute::get(fn (): array => collect($this->images ?? [])
            ->map(fn (string $image): string => $this->resolveImageUrl($image))
            ->all());
    }

    /**
     * URL de la portada (primera imagen), o null si no hay imágenes.
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image_urls[0] ?? null);
    }

    private function resolveImageUrl(string $image): string
    {
        if (str_starts_with($image, 'http')) {
            return $image;
        }

        return Storage::disk('public')->url($image);
    }
}
