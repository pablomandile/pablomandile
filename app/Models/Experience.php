<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['role', 'company', 'period', 'sort_order'];

    protected function casts(): array
    {
        return [
            'role' => 'array',
            'period' => 'array',
        ];
    }
}
