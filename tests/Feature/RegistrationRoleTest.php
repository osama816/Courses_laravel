<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles so they exist for assignment
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_new_user_gets_default_role()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // Check if user has 'student' role (assuming student is default)
        // If no logic assigns it, this assert might fail OR the user might have 'role' column set but not Spatie role.
        
        // Check Spatie role
        $this->assertTrue($user->hasRole('student'), 'User should have student role assigned via Spatie');
        
        // Check database column if used
        $this->assertEquals('student', $user->role, 'User role column should be student');
    }
}
