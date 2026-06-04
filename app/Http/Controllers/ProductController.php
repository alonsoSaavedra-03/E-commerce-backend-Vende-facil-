<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with('category:id,name,slug');

        $query->when($request->string('category')->toString(), function ($query, string $category): void {
            $query->whereHas('category', function ($categoryQuery) use ($category): void {
                $categoryQuery->where('slug', $category);
            });
        });

        $query->when($request->string('search')->toString(), function ($query, string $search): void {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $products = $query
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $products,
        ]);
    }

    public function store(Request $request): JsonResponse
    {

        if (is_string($request->input('specifications'))) {
            $request->merge([
                'specifications' => json_decode($request->input('specifications'), true)
            ]);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'specifications' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request->file('image'), $request);
        } elseif ($request->filled('image')) {
            $imagePath = $request->input('image');
        }

        $product = Product::create(array_merge($validated, ['image' => $imagePath]));

        return response()->json([
            'message' => 'Producto creado con éxito',
            'data' => $product->load('category:id,name,slug'),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'data' => $product->load('category:id,name,slug'),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {

        if (is_string($request->input('specifications'))) {
            $request->merge([
                'specifications' => json_decode($request->input('specifications'), true)
            ]);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'specifications' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request->file('image'), $request);
        } elseif ($request->filled('image') || $request->has('image')) {
            $imagePath = $request->input('image');
        }

        $product->update(array_merge($validated, ['image' => $imagePath]));

        return response()->json([
            'message' => 'Producto actualizado con éxito',
            'data' => $product->load('category:id,name,slug'),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado con éxito',
        ]);
    }

    private function handleImageUpload($uploadedFile, Request $request): ?string
    {
        $filename = null;

        // Try to convert to WebP using GD if loaded
        if (extension_loaded('gd')) {
            $filename = $this->convertToWebP($uploadedFile);
        }

        // If GD is not loaded, or WebP conversion failed, store original file natively
        if (!$filename) {
            $extension = $uploadedFile->getClientOriginalExtension() ?: 'png';
            $filename = 'products/' . \Illuminate\Support\Str::random(20) . '.' . $extension;
            $uploadedFile->storeAs('products', basename($filename), 'public');
        }

        // Build dynamic URL using requested scheme and host to avoid APP_URL issues in subdirectories
        return $request->getSchemeAndHttpHost() . $request->getBasePath() . '/storage/' . $filename;
    }

    private function convertToWebP($uploadedFile): ?string
    {
        $tempPath = $uploadedFile->getRealPath();
        $mimeType = $uploadedFile->getMimeType();

        // Load image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($tempPath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($tempPath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($tempPath);
                if ($image) {
                    imagepalettetotruecolor($image);
                }
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($tempPath);
                break;
            default:
                return null;
        }

        if (!$image) {
            return null;
        }

        // Generate a unique filename
        $filename = 'products/' . \Illuminate\Support\Str::random(20) . '.webp';
        $storePath = storage_path('app/public/' . $filename);

        // Make sure directory exists
        if (!file_exists(dirname($storePath))) {
            @mkdir(dirname($storePath), 0755, true);
        }

        // Save as WebP with 80% quality
        $saved = @imagewebp($image, $storePath, 80);
        @imagedestroy($image);

        return $saved ? $filename : null;
    }
}
