<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\SupportTicket;
use App\Models\User;

class CreateSupportTicketTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create a new support ticket for a user. Use this when a student needs help with payment issues, course access, or technical problems.
        The ticket will be automatically assigned a ticket number and can be tracked.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
        ]);
        $userId = $validated['user_id'] ?? null;
        $subject = $validated['subject'] ?? null;
        $message = $validated['message'] ?? null;
        $priority = $validated['priority'] ?? 'medium';

        if (!$userId || !$subject || !$message) {
            return Response::text('Error: user_id, subject, and message are required');
        }

        $user = User::find($userId);
        if (!$user) {
            return Response::text('Error: User not found');
        }

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $userId,
            'subject' => $subject,
            'message' => $message,
            'priority' => $priority,
            'status' => 'open',
        ]);

        return Response::text(json_encode([
            'success' => true,
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'status' => $ticket->status,
            'priority' => $ticket->priority,
            'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
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
                ->description('The ID of the user creating the ticket')
                ->required(),
            'subject' => $schema->string()
                ->title('Subject')
                ->description('Brief subject of the issue')
                ->required(),
            'message' => $schema->string()
                ->title('Message')
                ->description('Detailed description of the issue')
                ->required(),
            'priority' => $schema->string()
                ->title('Priority')
                ->description('Priority level: low, medium, high, urgent')
                ->enum(['low', 'medium', 'high', 'urgent'])
                ->default('medium'),
        ];
    }
}

//->optional()
