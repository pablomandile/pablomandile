<?php

namespace App\Models;

use App\Enums\TechCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    protected $fillable = ['name', 'category', 'icon', 'sort_order'];

    protected function casts(): array
    {
        return [
            'category' => TechCategory::class,
        ];
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
