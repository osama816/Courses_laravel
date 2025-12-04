@auth
<div class="ai-support-widget">
    <!-- Floating Button -->
    <button
        wire:click="toggleChat"
        class="ai-support-button {{ $isOpen ? 'active' : '' }}"
        aria-label="Open AI Support">
        @if(!$isOpen)
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        @else
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        @endif
    </button>

    <!-- Chat -->
    @if($isOpen)
    <div class="ai-support-chat"
        x-data
        x-init="$nextTick(() => $el.querySelector('.ai-support-messages').scrollTop = $el.querySelector('.ai-support-messages').scrollHeight)"
        @scroll-to-bottom.window="$nextTick(() => $el.querySelector('.ai-support-messages').scrollTop = $el.querySelector('.ai-support-messages').scrollHeight)">

        <!-- Header -->
        <div class="ai-support-header">
            <div class="d-flex align-items-center">
                <div class="ai-support-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0">AI Support Assistant</h6>
                    <small class="text-muted">{{ $isTyping ? 'Typing...' : 'Online' }}</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button wire:click="clearChat" class="btn btn-sm btn-link text-white" title="Clear chat">
                    <i class="bi bi-trash"></i>
                </button>
                <button wire:click="toggleChat" class="btn-close btn-close-white" aria-label="Close"></button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="ai-support-messages" wire:loading.class="opacity-75">
            @if(count($messages) === 0)
            <!-- Welcome Message -->
            <div class="ai-support-welcome">
                <div class="text-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <p class="text-center text-muted mb-0">
                    Hello! I'm your AI support assistant. How can I help you today?
                </p>
                <div class="mt-3">
                    <button wire:click="sendQuickMessage('What courses do I have?')" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="bi bi-book me-1"></i> My Courses
                    </button>
                    <button wire:click="sendQuickMessage('Check my payment status')" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="bi bi-credit-card me-1"></i> Payment Status
                    </button>
                    <button wire:click="sendQuickMessage('Show my invoices')" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-receipt me-1"></i> My Invoices
                    </button>
                </div>
            </div>
            @else
            <!-- Messages -->
            @foreach($messages as $index => $message)
            <div class="ai-support-message {{ $message['role'] === 'user' ? 'user-message' : 'assistant-message' }}"
                wire:key="message-{{ $index }}">
                <div class="message-content">
                    {!! $this->formatMessage($message['content']) !!}
                </div>
                <div class="message-time">
                    {{ $this->formatTime($message['timestamp']) }}
                </div>
            </div>
            @endforeach

            <!-- Typing Indicator -->
            @if($isTyping)
            <div class="ai-support-message assistant-message">
                <div class="message-content">
                    <span class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </div>
            @endif
            @endif
        </div>

        <!-- Input Area -->
        <div class="ai-support-input">
            <form wire:submit.prevent="sendMessage" class="d-flex gap-2">
                <input
                    type="text"
                    wire:model="inputMessage"
                    placeholder="Type your message..."
                    class="form-control form-control-sm"
                    @disabled($isTyping)
                    autofocus>

                <button
                    type="submit"
                    class="btn btn-primary btn-sm"
                    wire:loading.attr="disabled"
                    @disabled(!trim($inputMessage) || $isTyping)>
                    <span wire:loading.remove wire:target="sendMessage">
                        <i class="bi bi-send"></i>
                    </span>
                    <span wire:loading wire:target="sendMessage">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </span>
                </button>

            </form>
        </div>
    </div>
    @endif

@endauth
</div>
