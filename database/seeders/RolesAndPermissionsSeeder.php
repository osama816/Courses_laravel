<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

    /*
    |--------------------------------------------------------------------------
    | Course Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view courses', 'guard_name' => 'web']);
    Permission::create(['name' => 'create courses', 'guard_name' => 'web']);
    Permission::create(['name' => 'edit courses', 'guard_name' => 'web']);
    Permission::create(['name' => 'delete courses', 'guard_name' => 'web']);

    /*
    |--------------------------------------------------------------------------
    | Booking Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view bookings', 'guard_name' => 'web']);
    Permission::create(['name' => 'create bookings', 'guard_name' => 'web']);
    Permission::create(['name' => 'approve bookings', 'guard_name' => 'web']);
    Permission::create(['name' => 'cancel bookings', 'guard_name' => 'web']);
    Permission::create(['name' => 'update booking status', 'guard_name' => 'web']);

    /*
    |--------------------------------------------------------------------------
    | Payments Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view payments', 'guard_name' => 'web']);
    Permission::create(['name' => 'refund payments', 'guard_name' => 'web']);

    /*
    |--------------------------------------------------------------------------
    | User Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view users', 'guard_name' => 'web']);
    Permission::create(['name' => 'edit users', 'guard_name' => 'web']);


    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    // Admin Role
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo([
        // Courses
        'view courses', 'create courses', 'edit courses', 'delete courses',
        // Bookings
        'view bookings', 'create bookings', 'approve bookings', 'cancel bookings', 'update booking status',
        // Payments
        'view payments', 'refund payments',
        // Users
        'view users', 'edit users',
    ]);

    // Instructor Role
    $instructorRole = Role::create(['name' => 'instructor', 'guard_name' => 'web']);
    $instructorRole->givePermissionTo([
        'view courses',
        'create courses',
        'edit courses',

        // Can see bookings for his courses
        'view bookings',
        'update booking status',
    ]);

    // Student Role
    $studentRole = Role::create(['name' => 'student', 'guard_name' => 'web']);
    $studentRole->givePermissionTo([
        'view courses',

        // Booking permissions
        'create bookings',
        'view bookings',
        'cancel bookings',

        // Payment permissions (to access payment gateway)
        'view payments',
    ]);
}}


