<?php

namespace App\Services;

use App\Mcp\Servers\SupportServer;

class MCPLLMOrchestrator
{
    protected $mcpServer;
    protected $llmService;
    protected $conversationHistory = [];

    public function __construct(MCPService $MCPService, LLMService $llmService)
    {
        $this->mcpServer = $MCPService;
        $this->llmService = $llmService;
    }

    public function processQuery($userQuery)
    {
        // 1. الحصول على الـ tools المتاحة من MCP
        $availableTools = $this->getMCPTools();

        // 2. إرسال السؤال للـ LLM مع الـ tools
        $this->conversationHistory[] = [
            'role' => 'user',
            'content' => $userQuery
        ];

        $response = $this->llmService->sendMessage(
            $this->conversationHistory,
            $availableTools
        );

        // 3. معالجة tool calls إذا كان LLM طلبهم
        if (isset($response['content'])) {
            foreach ($response['content'] as $block) {
                if ($block['type'] === 'tool_use') {
                    $toolResult = $this->executeMCPTool(
                        $block['name'],
                        $block['input']
                    );

                    // 4. إرجاع نتيجة الـ tool للـ LLM
                    $this->conversationHistory[] = [
                        'role' => 'assistant',
                        'content' => $response['content']
                    ];

                    $this->conversationHistory[] = [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'tool_result',
                                'tool_use_id' => $block['id'],
                                'content' => json_encode($toolResult)
                            ]
                        ]
                    ];

                    // إعادة إرسال للـ LLM
                    return $this->processQuery('');
                }
            }
        }

        // 5. إرجاع النتيجة النهائية
        return $this->extractTextResponse($response);
    }

    protected function getMCPTools()
    {
        // الحصول على قائمة الـ tools من MCP server
        return $this->mcpServer->callTool('list_tools', [SupportServer::class]);
    }

    protected function executeMCPTool($toolName, $arguments)
    {
        return $this->mcpServer->callTool($toolName, $arguments);
    }

    protected function extractTextResponse($response)
    {
        $text = '';
        foreach ($response['content'] as $block) {
            if ($block['type'] === 'text') {
                $text .= $block['text'];
            }
        }
        return $text;
    }
}
