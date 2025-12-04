<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LLMService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        // للـ Claude API
        $this->apiKey = env('ANTHROPIC_API_KEY');
        $this->apiUrl = 'https://api.anthropic.com/v1/messages';
        $this->model = 'claude-sonnet-4-20250514';

        // أو للـ OpenAI
        // $this->apiKey = config('services.openai.api_key');
        // $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
        // $this->model = 'gpt-4';
    }

    public function sendMessage($messages, $tools = [])
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => $this->model,
            'max_tokens' => 4096,
            'messages' => $messages,
            'tools' => $tools,
        ]);

        return $response->json();
    }

    public function chat($userMessage, $mcpTools = [])
    {
        $messages = [
            ['role' => 'user', 'content' => $userMessage]
        ];

        // تحويل MCP tools لصيغة LLM
        $formattedTools = $this->formatMCPTools($mcpTools);

        $response = $this->sendMessage($messages, $formattedTools);

        return $response;
    }

    protected function formatMCPTools($mcpTools)
    {
        $formatted = [];

        foreach ($mcpTools as $tool) {
            $formatted[] = [
                'name' => $tool['name'],
                'description' => $tool['description'],
                'input_schema' => $tool['inputSchema'] ?? [
                    'type' => 'object',
                    'properties' => [],
                ]
            ];
        }

        return $formatted;
    }
}

