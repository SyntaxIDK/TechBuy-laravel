<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'iPhones',
                'description' => 'Latest Apple iPhones with cutting-edge technology',
                'is_active' => true,
            ],
            [
                'name' => 'MacBooks',
                'description' => 'Apple MacBook laptops for professionals and creators',
                'is_active' => true,
            ],
            [
                'name' => 'Android Phones',
                'description' => 'Premium Android smartphones from top brands',
                'is_active' => true,
            ],
            [
                'name' => 'Laptops',
                'description' => 'High-performance laptops for work and gaming',
                'is_active' => true,
            ],
            [
                'name' => 'Tablets',
                'description' => 'Tablets for productivity and entertainment',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'description' => 'Tech accessories and peripherals',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']], // Search criteria
                $category // Data to insert if not found
            );
        }
    }
}
