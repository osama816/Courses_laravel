<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\SupportServer;
use Illuminate\Support\Facades\Log;
use App\Services\MCPLLMOrchestrator;
use Illuminate\Support\Facades\Auth;

class McpSupportController extends Controller
{
    protected $orchestrator;

    public function __construct(MCPLLMOrchestrator $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }
    /**
     * Handle MCP support chat requests
     */
    public function chat(Request $request)
    {
        $userId =  Auth::id();
        $request->validate([
            'messages' => 'required|array',
        ]);


        if (!$userId) {
            return response()->json([
                'error' => 'The user id field .',
            ], 400);
        }
        if (Auth::check() && Auth::id() != $userId) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 403);
        }

        try {

            $messages = $request->messages;
            $lastMessage = end($messages);
            // $res=$this->orchestrator->processQuery($lastMessage);
            // Log::info('test', ['content' => $res]);
            if (!$lastMessage || $lastMessage['role'] !== 'user') {
                return response()->json([
                    'error' => 'Invalid message format',
                ], 400);
            }

            $response = $this->processMessage($lastMessage['content'], $userId);

            return response()->json([
                'content' => $response,
                'role' => 'assistant',
            ]);
        } catch (\Exception $e) {
            Log::error('MCP Support Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process message using MCP tools
     */
    private function processMessage(string $message, int $userId): string
    {
        $message = strtolower(trim($message));
        $userId = Auth::id();
        // Check for course-related queries
        if (str_contains($message, 'course') || str_contains($message, 'enroll')) {
            $tool = new \App\Mcp\Tools\GetUserCoursesTool();
            $request = new \Laravel\Mcp\Request([
                'params' => ['user_id' => $userId],
            ]);
            $data = $tool->handle($request);
            Log::info('Tool Response Content:', ['content' => $data]);


            if (!empty($data['courses'])) {
                $courses = $data['courses'];
                $response = "🎉 Hey {$data['user_name']}! You’re currently enrolled in " . count($courses) . " course(s):\n\n";

                foreach ($courses as $index => $course) {
                    $response .= ($index + 1) . ". **{$course['course_title']}**\n";
                    $response .= "   Status: ✅ " . ucfirst($course['status']) . "\n\n";
                }

                $response .= "Keep up the great work! 📚";
                return $response;
            } else {
                return "Hey {$data['user_name']}, you don’t have any courses yet. Check out our catalog and start learning today! 🌟";
            }
        }

        // Check for payment-related queries
        if (str_contains($message, 'payment') || str_contains($message, 'invoice') || str_contains($message, 'paid')) {
            $payments = \App\Models\Payment::whereHas('booking', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->with('invoice')->latest()->get();

            if ($payments->isEmpty()) {
                return "I couldn't find any payment records for your account.";
            }

 $response = "Your payments:<br><br>";

foreach ($payments as $payment) {
    $course = $payment->booking->course;

    $response .= "<p>Course: {$course->title}</p>";
  //  $response .= "<h3>Booking ID: {$payment->booking_id}</h3><ul>";
    $response .= "<li>Amount: $" . number_format($payment->amount, 2) . "</li>";
    $response .= "<li>Status: {$payment->status}</li>";
    $response .= "<li>Method: {$payment->payment_method}</li>";

    if ($payment->invoice) {
        $invoiceId = $payment->invoice->id;
        $viewLink = url("/invoice/{$invoiceId}");
        $downloadLink = url("/invoice/{$invoiceId}/download");

        $response .= "<li>Invoice:<div>";
        $response .= "<button onclick=\"window.location.href='{$viewLink}'\">View</button> ";
        $response .= "<button onclick=\"window.location.href='{$downloadLink}'\">Download</button>";
        $response .= "</div></li>";
    }

    $response .= "</ul><hr>";
}


            return $response;
        }




        // Default response
        return "I'm here to help! You can ask me about:\n\n" .
            "• Your enrolled courses\n" .
            "• Payment status and invoices\n" .
            "• Course information\n" .
            "• Technical support\n\n" .
            "Try asking: 'What courses do I have?' or 'Check my payment status'";
    }
    // private function processMessage(string $message, int $userId): string
    // {
    //     $message = strtolower(trim($message));
    //     $userId = Auth::id();
    //     // Check for course-related queries
    //     if (str_contains($message, 'course') || str_contains($message, 'enroll')) {
    //         $tool = new \App\Mcp\Tools\GetUserCoursesTool();
    //         $request = new \Laravel\Mcp\Request([
    //             'params' => ['user_id' => $userId],
    //         ]);
    //         $data = $tool->handle($request);
    //         Log::info('Tool Response Content:', ['content' => $data]);


    //         if (!empty($data['courses'])) {
    //             $courses = $data['courses'];
    //             $response = "🎉 Hey {$data['user_name']}! You’re currently enrolled in " . count($courses) . " course(s):\n\n";

    //             foreach ($courses as $index => $course) {
    //                 $response .= ($index + 1) . ". **{$course['course_title']}**\n";
    //                 $response .= "   Status: ✅ " . ucfirst($course['status']) . "\n\n";
    //             }

    //             $response .= "Keep up the great work! 📚";
    //             return $response;
    //         } else {
    //             return "Hey {$data['user_name']}, you don’t have any courses yet. Check out our catalog and start learning today! 🌟";
    //         }
    //     }

    //     // Check for payment-related queries
    //     if (str_contains($message, 'payment') || str_contains($message, 'invoice') || str_contains($message, 'paid')) {
    //         // Get user's latest payment
    //         $payment = \App\Models\Payment::whereHas('booking', function ($q) use ($userId) {
    //             $q->where('user_id', $userId);
    //         })->latest()->first();

    //         if ($payment) {
    //             $tool = new \App\Mcp\Tools\GetPaymentStatusTool();
    //             $request = new \Laravel\Mcp\Request([
    //                 'params' => ['payment_id' => $payment->id],
    //             ]);
    //             $data = $tool->handle($request);


    //             if ($data) {
    //                 return "Your payment status:\n\n" .
    //                     "• Amount: $" . number_format($data['amount'], 2) . "\n" .
    //                     "• Status: **{$data['status']}**\n" .
    //                     "• Method: {$data['payment_method']}\n" .
    //                     ($data['has_invoice'] ? "\nYou can download your invoice from your booking page." : "");
    //             }
    //         } else {
    //             return "I couldn't find any payment records for your account.";
    //         }
    //     }

    //     // Default response
    //     return "I'm here to help! You can ask me about:\n\n" .
    //         "• Your enrolled courses\n" .
    //         "• Payment status and invoices\n" .
    //         "• Course information\n" .
    //         "• Technical support\n\n" .
    //         "Try asking: 'What courses do I have?' or 'Check my payment status'";
    // }



    // public function chat(Request $request)
    // {
    //     log::info('MCP Support Chat Request: ', $request->all());
    //     $userId =  Auth::id();
    //     $request->validate([
    //         'messages' => 'required|array',
    //     ]);

    //     // $userId = $request->input('user_id', Auth::id()); // fallback على المستخدم المسجل
    //     if (!$userId) {
    //         return response()->json([
    //             'error' => 'The user id field .',
    //         ], 400);
    //     }
    //     // // Ensure user can only access their own chat
    //     // if (Auth::check() && Auth::id() != $userId) {
    //     //     return response()->json([
    //     //         'error' => 'Unauthorized',
    //     //     ], 403);
    //     // }

    //     try {
    //         // Get the last user message
    //         $messages = $request->messages;
    //         $lastMessage = end($messages);

    //         if (!$lastMessage || $lastMessage['role'] !== 'user') {
    //             return response()->json([
    //                 'error' => 'Invalid message format',
    //             ], 400);
    //         }

    //         // For now, return a simple response
    //         // In production, you would integrate with the actual MCP server
    //         $response = $this->processMessage($lastMessage['content'], $userId);

    //         return response()->json([
    //             'content' => $response,
    //             'role' => 'assistant',
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('MCP Support Error: ' . $e->getMessage());

    //         return response()->json([
    //             'error' => 'An error occurred while processing your request.',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

}
