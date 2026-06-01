<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('is_active', true)
            ->when($request->string('category')->toString(), function ($query, string $category): void {
                $query->whereHas('category', function ($categoryQuery) use ($category): void {
                    $categoryQuery
                        ->where('slug', $category)
                        ->where('is_active', true);
                });
            })
            ->orderBy('name')
            ->get([
                'id',
                'category_id',
                'name',
                'slug',
                'description',
                'price',
                'stock',
                'image',
                'is_active',
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }
}
