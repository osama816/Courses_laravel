<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class GetUserCoursesTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Get all courses that a user has booked. Returns course details including title, price, status, and booking information.
        Use this to help students see their enrolled courses and course progress.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request)
    {

        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
        ]);

        $userId = $validated['user_id'] ?? Auth::id();
        if (!$userId) {
            return Response::text('Error: user_id is required');
        }

        $user = User::find($userId);
        if (!$user) {
            return Response::text('Error: User not found');
        }

        $bookings = Booking::where('user_id', $userId)
            ->with(['course.category', 'course.instructor'])
            ->get();

        $courses = $bookings->map(function ($booking) {
            return [
                'booking_id' => $booking->id,
                'course_id' => $booking->course_id,
                'course_title' => $booking->course->title,
                'course_description' => $booking->course->description,
                'price' => $booking->course->price,
                'level' => $booking->course->level,
                'status' => $booking->status,
                'category' => $booking->course->category->name ?? 'N/A',
                'instructor' => $booking->course->instructor->user->name ?? 'N/A',
                'booked_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return [
            'user_id' => $userId,
            'user_name' => $user->name,
            'total_courses' => $courses->count(),
            'courses' => $courses->values()->all(),
        ];
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'user_id' => $schema->integer()
                ->title('User ID')
                ->description('The ID of the user to get courses for')
                ->required(),
        ];
    }
}
