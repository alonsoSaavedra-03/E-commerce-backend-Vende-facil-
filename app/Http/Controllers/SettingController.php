<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::pluck('value', 'key')->all();

        return response()->json([
            'data' => $settings,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $settings = Setting::pluck('value', 'key')->all();

        return response()->json([
            'message' => 'Configuraciones guardadas con éxito',
            'data' => $settings,
        ]);
    }

    public function clearCache(): JsonResponse
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return response()->json([
            'message' => 'Caché del sistema limpiada con éxito',
        ]);
    }

    public function seedDatabase(): JsonResponse
    {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return response()->json([
            'message' => 'Base de datos restablecida con éxito con datos semilla',
        ]);
    }
}
