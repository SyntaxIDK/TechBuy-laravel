<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        // Create test user only if it doesn't exist
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->withPersonalTeam()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
