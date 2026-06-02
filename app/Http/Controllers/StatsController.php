<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $averagePrice = Product::avg('price') ?: 0;
        
        $activeCategories = Category::where('is_active', true)->count();
        $activeProducts = Product::where('is_active', true)->count();

        $recentProducts = Product::with('category:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentCategories = Category::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $categoryStats = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get(['id', 'name']);

        $dbConnection = config('database.default');
        $dbName = config("database.connections.{$dbConnection}.database");
        $dbHost = config("database.connections.{$dbConnection}.host");

        return response()->json([
            'total_categories' => $totalCategories,
            'total_products' => $totalProducts,
            'total_stock' => (int) $totalStock,
            'average_price' => round($averagePrice, 2),
            'active_categories' => $activeCategories,
            'active_products' => $activeProducts,
            'recent_products' => $recentProducts,
            'recent_categories' => $recentCategories,
            'category_stats' => $categoryStats,
            'database' => [
                'connection' => $dbConnection,
                'name' => $dbName,
                'host' => $dbHost,
                'users_count' => \Illuminate\Support\Facades\DB::table('users')->count(),
                'categories_count' => $totalCategories,
                'products_count' => $totalProducts,
                'customers_count' => \Illuminate\Support\Facades\DB::table('customers')->count(),
            ]
        ]);
    }
}
