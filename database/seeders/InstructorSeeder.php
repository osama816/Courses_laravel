<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::factory()->count(1)->create();
    }
}
