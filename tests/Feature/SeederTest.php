<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\Course;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_runs_correctly()
    {
        // Run the DatabaseSeeder
        $this->seed();

        // Assert Roles are created
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'instructor']);
        $this->assertDatabaseHas('roles', ['name' => 'student']);

        // Assert Users are created
        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'instructor@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'student@example.com']);

        // Assert Categories are created
        $this->assertGreaterThan(0, Category::count());

        // Assert Instructors are created
        $this->assertGreaterThan(0, Instructor::count());

        // Assert Courses are created
        $this->assertGreaterThan(0, Course::count());
    }
}
