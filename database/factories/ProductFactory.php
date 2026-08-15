<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            'Laptop ASUS',
            'Laptop Lenovo',
            'Monitor LG 24 Inch',
            'Keyboard Logitech',
            'Mouse Logitech',
            'Printer Epson',
            'Kabel HDMI',
            'Kabel USB',
            'Flashdisk 64GB',
            'Harddisk Eksternal 1TB',
            'SSD 512GB',
            'Router TP-Link',
            'Switch 8 Port',
            'Proyektor Epson',
            'Kursi Kantor',
            'Meja Kantor',
            'Lemari Arsip',
            'Rak Penyimpanan',
            'Kertas A4',
            'Tinta Printer',
        ];

        return [
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'name' => fake()->unique()->randomElement($products),
            'sku' => 'SKU-' . strtoupper(fake()->unique()->bothify('??###')),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10000, 10000000),
            'stock' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(5, 20),
        ];
    }
}
