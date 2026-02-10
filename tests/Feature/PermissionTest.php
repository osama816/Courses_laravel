<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_student_has_correct_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $this->assertTrue($user->can('view courses'));
        $this->assertTrue($user->can('create bookings'));
        $this->assertTrue($user->can('view bookings'));
        $this->assertTrue($user->can('cancel bookings'));

        $this->assertFalse($user->can('create courses'));
        $this->assertFalse($user->can('edit courses'));
        $this->assertFalse($user->can('delete courses'));
    }

    public function test_instructor_has_correct_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('instructor');

        $this->assertTrue($user->can('view courses'));
        $this->assertTrue($user->can('create courses'));
        $this->assertTrue($user->can('edit courses'));
        $this->assertTrue($user->can('view bookings'));
        $this->assertTrue($user->can('update booking status'));

        // Check if instructor can cancel bookings? Seeder says NO.
        $this->assertFalse($user->can('cancel bookings'));
        
        // Check if instructor can delete courses? Seeder says NO.
        $this->assertFalse($user->can('delete courses'));
    }

    public function test_admin_has_correct_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->can('view courses'));
        $this->assertTrue($user->can('create courses'));
        $this->assertTrue($user->can('edit courses'));
        $this->assertTrue($user->can('delete courses'));
        $this->assertTrue($user->can('view bookings'));
        $this->assertTrue($user->can('create bookings'));
        $this->assertTrue($user->can('approve bookings'));
        $this->assertTrue($user->can('cancel bookings'));
        $this->assertTrue($user->can('update booking status'));
    }

    public function test_middleware_enforces_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        // Accessing a route protected by 'permission:create courses'
        // We need to mock a route or use an existing one. 
        // Existing: Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
        // Middleware: ['auth', 'permission:create courses']

        $response = $this->actingAs($user)->get(route('courses.create'));
        
        // Should be 403
        $response->assertStatus(403);
    }
}
