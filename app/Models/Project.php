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
        'preview_image',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $appends = ['preview_image_url'];

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    protected function previewImageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->preview_image) {
                return null;
            }

            if (str_starts_with($this->preview_image, 'http')) {
                return $this->preview_image;
            }

            return Storage::disk('public')->url($this->preview_image);
        });
    }
}
