<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        $this->call([
            CategorySeeder::class,
            ConditionSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
