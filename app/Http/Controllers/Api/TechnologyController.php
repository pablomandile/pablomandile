<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    /**
     * Nombres tal como están cargados, para que el agente los use al
     * asignarle tecnologías a un proyecto.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'technologies' => Technology::orderBy('category')
                ->orderBy('sort_order')
                ->get(['name', 'category']),
        ]);
    }
}
