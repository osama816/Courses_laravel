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
            
            if (!$lastMessage || $lastMessage['role'] !== 'user') {
                return response()->json([
                    'error' => 'Invalid message format',
                ], 400);
            }
             // Use the Orchestrator with User Context
            // $response = $this->orchestrator->processQuery($lastMessage['content'], [
            //     'user_id' => $userId,
            //     'user_name' => Auth::user()->name ?? 'Guest',
            // ]);

            // Use the hardcoded processMessage instead of AI due to API errors
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
        $userId = $userId;
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
                $response = "<div class='space-y-4'>";
                $response .= "<div class='flex items-center gap-2 mb-2'><span class='text-lg'>🎉</span><span class='font-bold text-slate-900 dark:text-white'>Hey {$data['user_name']}!</span></div>";
                $response .= "<p class='text-sm text-slate-500 dark:text-slate-400 mb-4'>You’re currently enrolled in <span class='text-primary font-bold'>" . count($courses) . "</span> course(s):</p>";

                foreach ($courses as $index => $course) {
                    $response .= "
                    <div class='p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm hover:border-primary/30 transition-colors'>
                        <div class='flex items-start gap-3'>
                            <div class='w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 text-primary font-bold text-xs'>" . ($index + 1) . "</div>
                            <div class='flex-1 min-w-0'>
                                <h4 class='font-bold text-slate-900 dark:text-white text-sm truncate'>{$course['course_title']}</h4>
                                <div class='flex items-center gap-1.5 mt-1'>
                                    <span class='w-1.5 h-1.5 rounded-full bg-emerald-500'></span>
                                    <span class='text-[10px] font-bold text-emerald-600 uppercase tracking-wider'>✅ " . ucfirst($course['status']) . "</span>
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                $response .= "<p class='text-xs font-bold text-slate-400 dark:text-slate-500 pt-2 italic text-center'>Keep up the great work! 📚</p></div>";
                return $response;
            } else {
                return "<div class='text-center py-4'><div class='text-4xl mb-3'>🌟</div><p class='text-sm font-bold text-slate-900 dark:text-white mb-1'>Hey {$data['user_name']}!</p><p class='text-sm text-slate-500 dark:text-slate-400'>You don’t have any courses yet. Check out our catalog and start learning today!</p></div>";
            }
        }

        // Check for payment-related queries
        if (str_contains($message, 'payment') || str_contains($message, 'invoice') || str_contains($message, 'paid')) {
            $payments = \App\Models\Payment::whereHas('booking', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->with(['invoice', 'booking.course'])->latest()->get();

            if ($payments->isEmpty()) {
                return "<div class='text-center py-4'><div class='text-4xl mb-3'>🔍</div><p class='text-sm text-slate-500 dark:text-slate-400'>I couldn't find any payment records for your account.</p></div>";
            }

            $response = "<div class='space-y-4'>";
            $response .= "<div class='flex items-center gap-2 mb-2'><i class='fa-solid fa-receipt text-primary'></i><span class='font-bold text-slate-900 dark:text-white'>Your Recent Payments</span></div>";

            foreach ($payments as $payment) {
                $course = $payment->booking->course;
                $statusColor = $payment->status === 'paid' ? 'emerald' : 'amber';
                
                $response .= "
                <div class='p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm'>
                    <div class='flex justify-between items-start mb-3'>
                        <h4 class='font-bold text-slate-900 dark:text-white text-sm leading-tight'>{$course->title}</h4>
                        <span class='shrink-0 px-2 py-0.5 rounded-md bg-{$statusColor}-500/10 text-{$statusColor}-600 text-[10px] font-bold uppercase'>" . ucfirst($payment->status) . "</span>
                    </div>
                    
                    <div class='grid grid-cols-2 gap-2 mb-4'>
                        <div class='p-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800'>
                            <span class='block text-[8px] uppercase font-bold text-slate-400 tracking-widest'>Amount</span>
                            <span class='text-xs font-bold text-slate-700 dark:text-slate-300'>$" . number_format($payment->amount, 2) . "</span>
                        </div>
                        <div class='p-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800'>
                            <span class='block text-[8px] uppercase font-bold text-slate-400 tracking-widest'>Method</span>
                            <span class='text-xs font-bold text-slate-700 dark:text-slate-300'>{$payment->payment_method}</span>
                        </div>
                    </div>";

                if ($payment->invoice) {
                    $invoiceId = $payment->invoice->id;
                    $viewLink = url("/invoice/{$invoiceId}");
                    $downloadLink = url("/invoice/{$invoiceId}/download");

                    $response .= "
                    <div class='flex gap-2 pt-2 border-t border-slate-50 dark:border-slate-800'>
                        <a href='{$viewLink}' class='flex-1 py-2 rounded-xl bg-primary text-white text-[10px] font-bold text-center hover:bg-primary-hover transition-colors'>View Invoice</a>
                        <a href='{$downloadLink}' class='flex-1 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-bold text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors'>Download</a>
                    </div>";
                }

                $response .= "</div>";
            }

            $response .= "</div>";
            return $response;


            return $response;
        }
        if (str_contains($message, 'hi')||str_contains($message, 'hello')) {
            return "Hello! How can I help you today?";
        }
        if (str_contains($message, 'thank')||str_contains($message, 'thanks')) {
            return "You're welcome!";
        }
        
            




        // Default response
        return "I'm here to help! You can ask me about:\n\n" .
            "• Your enrolled courses\n" .
            "• Payment status and invoices\n" .
            "• Course information\n" .
            "• Technical support\n\n" .
            "Try asking: 'What courses do I have?' or 'Check my payment status'";
    }
 

}
