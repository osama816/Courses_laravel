<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\course;

class GetCourseInfoTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Get detailed information about a specific course including title, description, price, instructor, category, available seats, and level.
        Use this to help students learn about courses and make informed decisions.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);
        $courseId = $validated['course_id'];
        if (!$courseId) {
            return Response::text('Error: course_id is required');
        }

        $course = course::with(['category', 'instructor.user'])->find($courseId);

        if (!$course) {
            return Response::text('Error: Course not found');
        }

        $data = [
            'course_id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'level' => $course->level,
            'rating' => $course->rating,
            'duration' => $course->duration,
            'total_seats' => $course->total_seats,
            'available_seats' => $course->available_seats,
            'category' => $course->category->name ?? 'N/A',
            'instructor_name' => $course->instructor->user->name ?? 'N/A',
            'instructor_bio' => $course->instructor->bio ?? 'N/A',
            'is_available' => $course->available_seats > 0,
        ];

        return Response::text(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'course_id' => $schema->number()
                ->description('The ID of the course to get information for')
                ->required(),
        ];
    }
}

