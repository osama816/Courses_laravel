<?php

// ===================================
// File: app/Livewire/AiSupport.php
// ===================================

namespace App\Livewire;


use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiSupport extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $inputMessage = '';
    public $isTyping = false;
    public $userId ;

    public function mount($userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->loadMessages();
    }

    public function loadMessages()
    {
        // Load messages from session or database
        $this->messages = session("ai_support_messages_{$this->userId}", []);
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;

        if ($this->isOpen) {
            $this->dispatch('chat-opened');
        }
    }

    public function sendQuickMessage($message)
    {
        $this->inputMessage = $message;
        $this->sendMessage();
    }

    public function sendMessage()
    {
        if (empty(trim($this->inputMessage)) || $this->isTyping) {
            return;
        }

        // Add user message
        $userMessage = [
            'role' => 'user',
            'content' => $this->inputMessage,
            'timestamp' => now()->toIso8601String(),
        ];

        $this->messages[] = $userMessage;
        $this->saveMessages();

        // Clear input
        $messageContent = $this->inputMessage;
        $this->inputMessage = '';

        // Set typing state
        $this->isTyping = true;

        // Call AI API
        try {
            $response = $this->callAiApi($messageContent);

            $assistantMessage = [
                'role' => 'assistant',
                'content' => $response['content'] ?? $response['message'] ?? 'I apologize, but I encountered an error. Please try again.',
                'timestamp' => now()->toIso8601String(),
            ];

            $this->messages[] = $assistantMessage;
            $this->saveMessages();

        } catch (\Exception $e) {
            Log::error('AI Support Error: ' . $e->getMessage());

            $errorMessage = [
                'role' => 'assistant',
                'content' => 'Sorry, I encountered an error. Please try again later.',
                'timestamp' => now()->toIso8601String(),
            ];

            $this->messages[] = $errorMessage;
            $this->saveMessages();
        } finally {
            $this->isTyping = false;
            $this->dispatch('scroll-to-bottom');
        }
    }

    protected function callAiApi($message)
    {
        // Prepare messages for API
        $apiMessages = array_map(function ($msg) {
            return [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }, $this->messages);

        // Call your AI API endpoint
      $response = Http::post(config('app.url') . '/mcp/support/chat', [
            'messages' => $apiMessages,
            'user_id' => Auth::id(),
        ]);

        return $response->json();
    }

    public function saveMessages()
    {
        session(["ai_support_messages_{$this->userId}" => $this->messages]);
    }

    public function clearChat()
    {
        $this->messages = [];
        $this->saveMessages();
        $this->dispatch('chat-cleared');
    }

    public function render()
    {
        return view('livewire.ai-support');
    }
}
