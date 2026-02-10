<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class LLMService
{
    protected $model;

    public function __construct()
    {
        $this->model = config('mcp.model', 'gpt-4o-mini');
    }

    /**
     * Send messages to OpenAI with optional tool definitions
     */
    public function sendMessage(array $messages, array $tools = [])
    {
        try {
            $params = [
                'model' => $this->model,
                'messages' => $messages,
            ];

            if (!empty($tools)) {
                $params['tools'] = $tools;
                $params['tool_choice'] = 'auto';
            }

            $response = OpenAI::chat()->create($params);

            return $response->toArray();
        } catch (\Exception $e) {
            Log::error('OpenAI Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * High-level chat interface for MCP tools
     */
    public function chat(string $userMessage, array $mcpTools = [])
    {
        $messages = [
            ['role' => 'user', 'content' => $userMessage]
        ];

        $formattedTools = $this->formatToolsForOpenAI($mcpTools);

        return $this->sendMessage($messages, $formattedTools);
    }

    /**
     * Format MCP tools to OpenAI tool specification
     */
    protected function formatToolsForOpenAI(array $mcpTools): array
    {
        $formatted = [];

        foreach ($mcpTools as $tool) {
            $formatted[] = [
                'type' => 'function',
                'function' => [
                    'name' => $tool['name'],
                    'description' => $tool['description'],
                    'parameters' => $tool['inputSchema'] ?? [
                        'type' => 'object',
                        'properties' => (object)[],
                    ],
                ],
            ];
        }

        return $formatted;
    }
}

