<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
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
        // Admin
        User::create([
            'name' => 'Admin Warehouse',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Staff Warehouse',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Categories
        $categories = Category::factory(5)->create();

        // Suppliers
        $suppliers = Supplier::factory(5)->create();

        // Products
        Product::factory(20)->create([
            'category_id' => $categories->random()->id,
            'supplier_id' => $suppliers->random()->id,
        ]);
    }
}
