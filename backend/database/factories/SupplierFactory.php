<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        $suppliers = [
            'PT Sumber Jaya Teknologi',
            'CV Mitra Komputer Indonesia',
            'PT Nusantara Perkasa',
            'CV Berkah Mandiri',
            'PT Indo Supply Sejahtera',
            'CV Sentosa Abadi',
            'PT Cipta Sarana Teknologi',
            'CV Makmur Jaya',
        ];

        return [
            'name' => fake()->unique()->randomElement($suppliers),
            'phone' => '08' . fake()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
        ];
    }
}
