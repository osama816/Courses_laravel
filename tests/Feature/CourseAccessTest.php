<?php

namespace Tests\Feature;

use App\Models\course;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_course_details_with_valid_data()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'student']);
        
        $instructorUser = User::factory()->create(['name' => 'Dr. Smith', 'role' => 'instructor']);
        $instructor = Instructor::create([
            'user_id' => $instructorUser->id,
            'bio' => 'Test Bio',
            'avatar_url' => 'test.jpg'
        ]);

        $category = Category::factory()->create();

        $course = course::create([
            'title' => ['en' => 'Test Course', 'ar' => 'Test Course'],
            'description' => ['en' => 'Description', 'ar' => 'Description'],
            'image_url' => 'course.jpg',
            'price' => 100,
            'level' => 'Beginner',
            'total_seats' => 10,
            'available_seats' => 10,
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('courses.show', $course->id));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Test Course');
        $response->assertSee('Dr. Smith');
    }
}
