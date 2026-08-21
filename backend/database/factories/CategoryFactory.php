<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Elektronik',
            'Peralatan Komputer',
            'Perlengkapan Kantor',
            'Alat Tulis Kantor',
            'Perabot Kantor',
            'Peralatan Jaringan',
            'Peralatan Penyimpanan',
            'Perlengkapan Printer',
        ];

        return [
            'name' => fake()->unique()->randomElement($categories),
            'description' => fake()->sentence(),
        ];
    }
}
