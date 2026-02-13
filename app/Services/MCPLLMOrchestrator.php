<?php

namespace App\Services;

use Laravel\Mcp\Facades\Mcp;
use Illuminate\Support\Facades\Log;
use App\Services\LLMService;

class MCPLLMOrchestrator
{
    protected $llmService;
    protected $conversationHistory = [];

    public function __construct(LLMService $llmService)
    {
        $this->llmService = $llmService;
    }

    /**
     * Process user query through LLM with MCP tool support
     */
    public function processQuery(string $userQuery, array $context = [])
    {
        // Add User Context to History if it's the first message
        if (empty($this->conversationHistory)) {
            $user_id = $context['user_id'] ?? 'Unknown';
            $user_name = $context['user_name'] ?? 'Guest';

            $this->conversationHistory[] = [
                'role' => 'system',
                'content' => "You are a helpful student support assistant.
                              The current user is: $user_name (ID: $user_id).
                              Use the user_id $user_id when calling tools like GetUserCoursesTool or GetPaymentStatusTool.
                              Always verify data using tools before answering specific account questions."
            ];
        }

        if (!empty($userQuery)) {
            $this->conversationHistory[] = [
                'role' => 'user',
                'content' => $userQuery
            ];
        }

        // 1. Get available tools from all registered MCP servers
        $mcpTools = Mcp::listTools();
        $formattedTools = $this->formatToolsForLLM($mcpTools);

        // 2. Send query to LLM
        $response = $this->llmService->sendMessage(
            $this->conversationHistory,
            $formattedTools
        );

        $message = $response['choices'][0]['message'] ?? null;

        if (!$message) {
            return "Sorry, I couldn't process that request.";
        }

        // Add assistant's message (which might contain tool calls) to history
        $this->conversationHistory[] = $message;

        // 3. Handle Tool Calls
        if (!empty($message['tool_calls'])) {
            foreach ($message['tool_calls'] as $toolCall) {
                $toolName = $toolCall['function']['name'];
                $arguments = json_decode($toolCall['function']['arguments'], true);

                Log::info("MCP Tool Execution: $toolName", ['args' => $arguments]);

                try {
                    // Call the tool via official MCP facade
                    $result = Mcp::callTool($toolName, $arguments);

                    $this->conversationHistory[] = [
                        'role' => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'name' => $toolName,
                        'content' => json_encode($result),
                    ];
                } catch (\Exception $e) {
                    Log::error("MCP Tool Failed: $toolName", ['error' => $e->getMessage()]);
                    $this->conversationHistory[] = [
                        'role' => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'name' => $toolName,
                        'content' => json_encode(['error' => $e->getMessage()]),
                    ];
                }
            }

            // 4. Send tool results back to LLM to get final response
            return $this->processQuery('');
        }

        // 5. Return final text response
        return $message['content'] ?? "I'm not sure how to respond to that.";
    }

    /**
     * Format MCP tool list for OpenAI
     */
    protected function formatToolsForLLM(array $mcpTools): array
    {
        $formatted = [];
        foreach ($mcpTools as $tool) {
            $formatted[] = [
                'type' => 'function',
                'function' => [
                    'name' => $tool->name,
                    'description' => $tool->description,
                    'parameters' => $tool->inputSchema ?? [
                        'type' => 'object',
                        'properties' => (object)[],
                    ],
                ],
            ];
        }
        return $formatted;
    }
}
