@props(['userId' => null])

@auth
@php
$currentUserId = $userId ?? Auth::id();
@endphp

<div x-data="aiSupportWidget({{ $currentUserId }})" class="ai-support-widget">
    <!-- Floating Button -->
<button
    @click="toggleChat"
    class="ai-support-button"
    :class="{ 'active': isOpen }"
    aria-label="Open AI Support">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
      <path d="M6 1a1 1 0 0 0-1 1v2H3a1 1 0 0 0-1 1v7h12V5a1 1 0 0 0-1-1h-2V2a1 1 0 0 0-1-1H6zm0 1h4v2H6V2zM2 6v6h12V6H2zm2 1h2v2H4V7zm4 0h2v2H8V7z"/>
    </svg>
</button>
    <!-- Chat Widget -->
    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="ai-support-chat">
        <div class="ai-support-header">
            <div class="d-flex align-items-center">
                <div class="ai-support-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0">AI Support Assistant</h6>
                    <small class="text-muted" x-text="isTyping ? 'Typing...' : 'Online'"></small>
                </div>
            </div>
            <button @click="toggleChat" class="btn-close btn-close-white" aria-label="Close"></button>
            <button @click="clearMessages"class="btn btn-sm btn-danger ms-2 p-1"title="Clear Chat"><i class="bi bi-trash"></i></button>

        </div>

        <div class="ai-support-messages" x-ref="messagesContainer">
            <template x-if="messages.length === 0">
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
                        <button @click="sendQuickMessage('What courses do I have?')" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="bi bi-book me-1"></i> My Courses
                        </button>
                        <button @click="sendQuickMessage('Check my payment status')" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="bi bi-credit-card me-1"></i> Payment Status
                        </button>
                        <button @click="sendQuickMessage('Show my invoices')" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-receipt me-1"></i> My Invoices
                        </button>
                    </div>
                </div>
            </template>

            <template x-for="(message, index) in messages" :key="index">
                <div class="ai-support-message" :class="message.role === 'user' ? 'user-message' : 'assistant-message'">
                    <div class="message-content" x-html="formatMessage(message.content)"></div>
                    <div class="message-time" x-text="formatTime(message.timestamp)"></div>
                </div>
            </template>

            <div x-show="isTyping" class="ai-support-message assistant-message">
                <div class="message-content">
                    <span class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="ai-support-input">
            <form @submit.prevent="sendMessage" class="d-flex gap-2">
                <input
                    type="text"
                    x-model="inputMessage"
                    @keydown.enter.prevent="sendMessage"
                    placeholder="Type your message..."
                    class="form-control form-control-sm"
                    :disabled="isTyping"
                    x-ref="inputField">
                <button
                    type="submit"
                    class="btn btn-primary btn-sm"
                    :disabled="!inputMessage.trim() || isTyping">
                    <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function aiSupportWidget(userId) {
        return {
            isOpen: false,
            messages: [],
            inputMessage: '',
            isTyping: false,
            conversationId: null,

            init() {
                // Load conversation history from localStorage
                const savedMessages = localStorage.getItem(`ai_support_messages_${userId}`);
                if (savedMessages) {
                    this.messages = JSON.parse(savedMessages);
                }
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.$refs.inputField?.focus();
                        this.scrollToBottom();
                    });
                }
            },

            sendQuickMessage(message) {
                this.inputMessage = message;
                this.sendMessage();
            },

            async sendMessage() {
                if (!this.inputMessage.trim() || this.isTyping) return;

                const userMessage = {
                    role: 'user',
                    content: this.inputMessage,
                    timestamp: new Date(),
                };

                this.messages.push(userMessage);
                this.saveMessages();
                this.inputMessage = '';
                this.isTyping = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('/mcp/support/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({
                            messages: this.messages.map(m => ({
                                role: m.role,
                                content: m.content,
                            })),

                        }),
                    });

                    const data = await response.json();

                    const assistantMessage = {
                        role: 'assistant',
                        content: data.content || data.message || 'I apologize, but I encountered an error. Please try again.',
                        timestamp: new Date(),
                    };

                    this.messages.push(assistantMessage);
                    this.saveMessages();
                } catch (error) {
                    console.error('Error:', error);
                    const errorMessage = {
                        role: 'assistant',
                        content: 'Sorry, I encountered an error. Please try again later.',
                        timestamp: new Date(),
                    };
                    this.messages.push(errorMessage);
                    this.saveMessages();
                } finally {
                    this.isTyping = false;
                    this.scrollToBottom();
                }
            },

            formatMessage(content) {
                // Simple markdown-like formatting
                return content
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/\n/g, '<br>');
            },

            formatTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = now - date;
                const minutes = Math.floor(diff / 60000);

                if (minutes < 1) return 'Just now';
                if (minutes < 60) return `${minutes}m ago`;
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            },

            saveMessages() {
                localStorage.setItem(`ai_support_messages_${userId}`, JSON.stringify(this.messages));
            },
            clearMessages() {
                    this.messages = [];
                    localStorage.removeItem(`ai_support_messages_${userId}`);
            }

        };
    }
</script>
@endauth
