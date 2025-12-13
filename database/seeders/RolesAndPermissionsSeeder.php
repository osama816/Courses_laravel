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
    Permission::create(['name' => 'view courses']);
    Permission::create(['name' => 'create courses']);
    Permission::create(['name' => 'edit courses']);
    Permission::create(['name' => 'delete courses']);

    /*
    |--------------------------------------------------------------------------
    | Lessons Permissions
    |--------------------------------------------------------------------------
    */
    // Permission::create(['name' => 'view lessons']);
    // Permission::create(['name' => 'create lessons']);
    // Permission::create(['name' => 'edit lessons']);
    // Permission::create(['name' => 'delete lessons']);

    /*
    |--------------------------------------------------------------------------
    | Booking Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view bookings']);
    Permission::create(['name' => 'create bookings']);
    Permission::create(['name' => 'approve bookings']);
    Permission::create(['name' => 'cancel bookings']);
    Permission::create(['name' => 'update booking status']);

    /*
    |--------------------------------------------------------------------------
    | Payments Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view payments']);
    Permission::create(['name' => 'refund payments']);

    /*
    |--------------------------------------------------------------------------
    | User Permissions
    |--------------------------------------------------------------------------
    */
    Permission::create(['name' => 'view users']);
    Permission::create(['name' => 'edit users']);


    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    // Admin Role
    $adminRole = Role::create(['name' => 'admin']);
    $adminRole->givePermissionTo([
        // Courses
        'view courses', 'create courses', 'edit courses', 'delete courses',
        // Lessons
       // 'view lessons', 'create lessons', 'edit lessons', 'delete lessons',
        // Bookings
        'view bookings', 'create bookings', 'approve bookings', 'cancel bookings', 'update booking status',
        // Payments
        'view payments', 'refund payments',
        // Users
        'view users', 'edit users',
    ]);

    // Instructor Role
    $instructorRole = Role::create(['name' => 'instructor']);
    $instructorRole->givePermissionTo([
        'view courses',
        'create courses',
        'edit courses',

        // 'view lessons',
        // 'create lessons',
        // 'edit lessons',

        // Can see bookings for his courses
        'view bookings',
        'update booking status',
    ]);

    // Student Role
    $studentRole = Role::create(['name' => 'student']);
    $studentRole->givePermissionTo([
        'view courses',
       // 'view lessons',

        // Booking permissions
        'create bookings',
        'view bookings',
        'cancel bookings',
    ]);
}}


