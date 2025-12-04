<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\SupportTicket;

class UpdateStudentIssueStatusTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Update the status of a support ticket. Use this to mark tickets as resolved, in progress, or closed.
        This helps track the resolution of student issues.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'ticket_id' => 'required|integer|exists:support_tickets,id',
            'status' => 'required|string|in:open,in_progress,resolved,closed',
        ]);
        $ticketId = $validated['ticket_id'] ?? null;
        $status = $validated['status'] ?? null;

        if (!$ticketId || !$status) {
            return Response::text('Error: ticket_id and status are required');
        }

        $validStatuses = ['open', 'in_progress', 'resolved', 'closed'];
        if (!in_array($status, $validStatuses)) {
            return Response::text('Error: Invalid status. Must be one of: ' . implode(', ', $validStatuses));
        }

        $ticket = SupportTicket::find($ticketId);
        if (!$ticket) {
            return Response::text('Error: Ticket not found');
        }

        $updateData = ['status' => $status];
        if ($status === 'resolved' || $status === 'closed') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        return Response::text(json_encode([
            'success' => true,
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'status' => $ticket->status,
            'resolved_at' => $ticket->resolved_at ? $ticket->resolved_at->format('Y-m-d H:i:s') : null,
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ticket_id' => $schema->integer()
                ->title('Ticket ID')
                ->description('The ID of the ticket to update')
                ->required(),
            'status' => $schema->string()
                ->title('Status')
                ->description('New status: open, in_progress, resolved, closed')
                ->enum(['open', 'in_progress', 'resolved', 'closed'])
                ->required(),
        ];
    }
}

