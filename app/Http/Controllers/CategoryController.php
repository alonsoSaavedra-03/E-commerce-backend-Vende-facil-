<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount([
                'products' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'description',
                'is_active',
            ]);

        return response()->json([
            'data' => $categories,
        ]);
    }
}
