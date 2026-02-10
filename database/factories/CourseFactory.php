<?php

namespace Database\Factories;
use App\Models\Instructor;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

            $fakerAr = \Faker\Factory::create('ar_SA');

        return [
            'title' => [
                'en' => $this->faker->sentence(3),
                'ar' => $fakerAr->realText(30),
            ],

            'description' => [
                'en' => $this->faker->paragraph(2),
                'ar' => $fakerAr->realText(150),
            ],

            'image_url' => 'courses/course-placeholder.jpg',
            'price' => $this->faker->randomFloat(2, 20, 300),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'total_seats' => $this->faker->numberBetween(10, 100),
            'available_seats' => $this->faker->numberBetween(0, 100),
            'rating' => $this->faker->numberBetween(0, 5),
            'duration' => $this->faker->numberBetween(1, 20) . ' hours',

            'instructor_id' =>  Instructor::factory()??Instructor::inRandomOrder()->first()->id ,
            'category_id' =>  Category::factory()??Category::inRandomOrder()->first()->id ,
        ];
    }
}
