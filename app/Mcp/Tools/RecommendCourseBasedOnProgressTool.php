<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;
use App\Models\course;
use App\Models\Booking;

class RecommendCourseBasedOnProgressTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Recommend courses to a user based on their current course enrollments and progress.
        Analyzes the user's enrolled courses and suggests related courses in the same category or at the next level.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
                $validated = $request->validate([
                'user_id'=>['required','exists:users,id'],
        ]);
        $userId = $validated['user_id'] ?? null;

        if (!$userId) {
            return Response::text('Error: user_id is required');
        }

        $user = User::find($userId);
        if (!$user) {
            return Response::text('Error: User not found');
        }

        // Get user's enrolled courses
        $userBookings = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->with('course.category')
            ->get();

        if ($userBookings->isEmpty()) {
            // If no courses, recommend beginner courses
            $recommendations = course::where('level', 'Beginner')
                ->where('available_seats', '>', 0)
                ->with(['category', 'instructor.user'])
                ->orderBy('rating', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Get categories and levels from enrolled courses
            $categories = $userBookings->pluck('course.category_id')->filter()->unique();
            $levels = $userBookings->pluck('course.level')->unique();

            // Get enrolled course IDs to exclude
            $enrolledCourseIds = $userBookings->pluck('course_id')->toArray();

            // Recommend courses in same categories or next level
            $recommendations = course::where('available_seats', '>', 0)
                ->whereNotIn('id', $enrolledCourseIds)
                ->where(function ($query) use ($categories, $levels) {
                    $query->whereIn('category_id', $categories)
                        ->orWhereIn('level', $levels);
                })
                ->with(['category', 'instructor.user'])
                ->orderBy('rating', 'desc')
                ->limit(5)
                ->get();
        }

        $recommendationsData = $recommendations->map(function ($course) {
            return [
                'course_id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'level' => $course->level,
                'rating' => $course->rating,
                'category' => $course->category->name ?? 'N/A',
                'instructor' => $course->instructor->user->name ?? 'N/A',
                'available_seats' => $course->available_seats,
            ];
        });

        return Response::text(json_encode([
            'user_id' => $userId,
            'user_name' => $user->name,
            'enrolled_courses_count' => $userBookings->count(),
            'recommendations' => $recommendationsData->values()->all(),
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'user_id' => $schema->integer()
                ->title('User ID')
                ->description('The ID of the user to recommend courses for')
                ->required(),
        ];
    }
}

