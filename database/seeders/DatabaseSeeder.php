<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::updateOrCreate([
            'email' => 'admin@vendefacil.com',
        ], [
            'name' => 'Administrador',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Seed Categories
        $electronics = Category::updateOrCreate([
            'slug' => 'electronicos',
        ], [
            'name' => 'Electronicos',
            'description' => 'Productos tecnológicos, gadgets y dispositivos de última generación.',
            'is_active' => true,
        ]);

        $deportes = Category::updateOrCreate([
            'slug' => 'deportes',
        ], [
            'name' => 'Deportes',
            'description' => 'Artículos, ropa y equipamiento deportivo de alta calidad.',
            'is_active' => true,
        ]);

        $hogar = Category::updateOrCreate([
            'slug' => 'hogar',
        ], [
            'name' => 'Hogar',
            'description' => 'Muebles, decoración y artículos esenciales para tu casa.',
            'is_active' => true,
        ]);

        $libros = Category::updateOrCreate([
            'slug' => 'libros',
        ], [
            'name' => 'Libros',
            'description' => 'Novelas, literatura científica, ficción y mucho más.',
            'is_active' => true,
        ]);

        $moda = Category::updateOrCreate([
            'slug' => 'moda',
        ], [
            'name' => 'Moda',
            'description' => 'Ropa, zapatos, calzado y accesorios de vestir modernos.',
            'is_active' => true,
        ]);

        // 3. Seed Products
        Product::updateOrCreate([
            'slug' => 'laptop-lenovo',
        ], [
            'category_id' => $electronics->id,
            'name' => 'Laptop Lenovo',
            'description' => 'Laptop Lenovo Ideapad de 15.6 pulgadas con procesador AMD, 8GB de RAM y 256GB SSD.',
            'price' => 12500.00,
            'stock' => 10,
            'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500&auto=format&fit=crop&q=60',
            'specifications' => [
                'Procesador' => 'AMD Ryzen 5 5500U',
                'Memoria RAM' => '8GB DDR4 SO-DIMM',
                'Almacenamiento' => '256GB SSD NVMe PCIe',
                'Pantalla' => '15.6 pulgadas Full HD (1920x1080)',
                'Sistema Operativo' => 'Windows 11 Home',
            ],
            'is_active' => true,
        ]);

        Product::updateOrCreate([
            'slug' => 'audifonos-sony',
        ], [
            'category_id' => $electronics->id,
            'name' => 'Audífonos Inalámbricos Sony',
            'description' => 'Audífonos Bluetooth de diadema Sony WH-CH520 con batería de hasta 50 horas y micrófono integrado.',
            'price' => 1100.00,
            'stock' => 15,
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60',
            'specifications' => [
                'Conectividad' => 'Bluetooth 5.2 (Multipunto)',
                'Autonomía' => 'Hasta 50 horas de reproducción',
                'Carga Rápida' => '3 minutos de carga dan 1.5 horas de uso',
                'Tipo de Carga' => 'USB Tipo C',
                'Micrófono' => 'Integrado con cancelación de ruido para llamadas',
            ],
            'is_active' => true,
        ]);

        Product::updateOrCreate([
            'slug' => 'balon-futbol-nike',
        ], [
            'category_id' => $deportes->id,
            'name' => 'Balón de Fútbol Nike Strike',
            'description' => 'Balón de fútbol oficial Nike con ranuras aerodinámicas diseñadas para un vuelo uniforme.',
            'price' => 499.00,
            'stock' => 30,
            'image' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=500&auto=format&fit=crop&q=60',
            'specifications' => [
                'Marca' => 'Nike',
                'Tamaño' => 'Número 5 (Medida oficial)',
                'Composición' => '60% Goma, 15% Poliuretano, 13% Poliéster, 12% EVA',
                'Aerodinámica' => 'Ranuras Nike Aerowsculpt para vuelo estable',
                'Construcción' => 'Cosido a máquina para mayor durabilidad',
            ],
            'is_active' => true,
        ]);

        Product::updateOrCreate([
            'slug' => 'lampara-led-escritorio',
        ], [
            'category_id' => $hogar->id,
            'name' => 'Lámpara de Escritorio LED',
            'description' => 'Lámpara regulable de mesa con brazo articulado, 5 modos de brillo y puerto USB para cargar celular.',
            'price' => 650.00,
            'stock' => 12,
            'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&auto=format&fit=crop&q=60',
            'specifications' => [
                'Iluminación' => 'LED de bajo consumo (10W)',
                'Modos de Brillo' => '5 niveles táctiles',
                'Temperatura de Color' => '3000K - 6500K (Cálida/Fría)',
                'Puerto USB' => 'Salida 5V/1A para carga de dispositivos',
                'Brazo' => 'Articulado ajustable hasta 180 grados',
            ],
            'is_active' => true,
        ]);

        Product::updateOrCreate([
            'slug' => 'tenis-deportivos',
        ], [
            'category_id' => $deportes->id,
            'name' => 'Tenis Deportivos Running',
            'description' => 'Tenis cómodos para correr o entrenar, con suela amortiguada de espuma y malla transpirable.',
            'price' => 1899.00,
            'stock' => 8,
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=60',
            'specifications' => [
                'Categoría' => 'Running / Entrenamiento',
                'Suela' => 'Goma antideslizante con media suela de EVA',
                'Ajuste' => 'Cordones clásicos',
                'Transpirabilidad' => 'Malla superior de ingeniería transpirable',
                'Peso aproximado' => '240g (depende de la talla)',
            ],
            'is_active' => true,
        ]);

        // 4. Seed Settings
        \App\Models\Setting::updateOrCreate(['key' => 'store_name'], ['value' => 'VendeFácil']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_email'], ['value' => 'contacto@vendefacil.com']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_phone'], ['value' => '+52 (664) 123-4567']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_currency'], ['value' => 'MXN']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_shipping_fee'], ['value' => '99.00']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_free_shipping_threshold'], ['value' => '999.00']);
        \App\Models\Setting::updateOrCreate(['key' => 'store_address'], ['value' => 'Av. de la Constitución 123, Tijuana, B.C., México']);
        \App\Models\Setting::updateOrCreate(['key' => 'system_maintenance'], ['value' => '0']);
    }
}

