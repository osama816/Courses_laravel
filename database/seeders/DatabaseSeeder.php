<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
     course::factory()->count(6)->create();
\App\Models\Category::factory(5)->create();
\App\Models\User::factory(5)->create();
\App\Models\Instructor::factory()->count(5)->create([
    'user_id' => User::inRandomOrder()->first()->id
]);


    }
}
