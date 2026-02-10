@props(['userId' => null])

@auth
@php
$currentUserId = $userId ?? Auth::id();
@endphp

<div x-data="aiSupportWidget({{ $currentUserId }})" class="fixed bottom-6 right-6 z-[100]">
    <!-- Floating Button -->
    <button
        @click="toggleChat"
        class="w-16 h-16 rounded-full bg-primary text-white flex items-center justify-center shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all duration-300 group"
        :class="{ 'rotate-12 bg-slate-900 shadow-slate-900/40': isOpen }"
        aria-label="Open AI Support">
        <template x-if="!isOpen">
            <img src="{{ asset('assets/chat.png') }}" alt="Chat" class="w-16 h-16 group-hover:animate-bounce">
        </template>
        <template x-if="isOpen">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </template>
    </button>

    <!-- Chat Widget -->
    <div x-show="isOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="absolute bottom-20 right-0 w-[400px] max-w-[calc(100vw-48px)] h-[600px] max-h-[calc(100vh-120px)] bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-3xl border border-slate-200/50 dark:border-slate-800/50 flex flex-col overflow-hidden backdrop-blur-xl">
        
        <!-- Header -->
        <div class="p-6 bg-slate-900 text-white flex justify-between items-center relative overflow-hidden">
            <!-- Animation Background -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
            
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/10 p-2">
                    <img src="{{ asset('assets/chat.png') }}" alt="AI" class="w-12 h-12 object-contain">
                </div>
                <div>
                    <h6 class="font-bold text-lg leading-tight">AI Assistant</h6>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="isTyping ? 'Thinking...' : 'Online Now'"></span>
                    </div>
                </div>
            </div>
            <div class="relative flex items-center gap-4">
                <button @click="clearMessages" class="w-10 h-10 p-2 text-red-500 flex items-center justify-center rounded-xl hover:bg-white/10 hover:text-white transition-all" title="Clear History">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
                <button @click="toggleChat" class="w-10 h-10 p-2 flex items-center justify-center rounded-xl hover:bg-white/10 text-yellow-500 hover:text-white transition-all" aria-label="Close">
                    <i class="fa-solid fa-minus text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-grow overflow-y-auto px-6 py-8 space-y-6 scrollbar-none" x-ref="messagesContainer">
            <template x-if="messages.length === 0">
                <div class="text-center py-10 space-y-8">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full"></div>
                        <div class="relative w-24 h-24 bg-surface dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto shadow-xl">
                            <i class="fa-solid fa-face-smile-wink text-4xl text-primary"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-xl font-bold text-text-base">Marhaba! (Hello)</h4>
                        <p class="text-sm text-text-muted max-w-[240px] mx-auto leading-relaxed">I'm your learning companion. Ask me anything about your journey!</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 pt-4">
                        <button @click="sendQuickMessage('What courses do I have?')" class="group flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-200/50 dark:border-slate-800 hover:border-primary hover:bg-primary/5 transition-all text-left">
                            <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><i class="fa-solid fa-book-bookmark text-primary group-hover:scale-110 transition-transform"></i></span>
                            <span class="text-sm font-bold text-text-base">My Enrolled Courses</span>
                        </button>
                        <button @click="sendQuickMessage('Check my payment status')" class="group flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-200/50 dark:border-slate-800 hover:border-primary hover:bg-primary/5 transition-all text-left">
                            <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><i class="fa-solid fa-receipt text-primary group-hover:scale-110 transition-transform"></i></span>
                            <span class="text-sm font-bold text-text-base">Payment Records</span>
                        </button>
                    </div>
                </div>
            </template>

            <template x-for="(message, index) in messages" :key="index">
                <div class="flex flex-col animate-in fade-in slide-in-from-bottom-2 duration-300" :class="message.role === 'user' ? 'items-end' : 'items-start'">
                    <div class="max-w-[85%] px-5 py-3.5 rounded-[1.5rem] text-sm leading-relaxed shadow-sm"
                         :class="message.role === 'user' 
                            ? 'bg-primary text-white rounded-br-none' 
                            : 'bg-surface dark:bg-slate-800 text-text-base rounded-tl-none border border-slate-100 dark:border-slate-800'">
                        <div x-html="formatMessage(message.content)" class="prose-sm overflow-hidden text-wrap break-words"></div>
                    </div>
                    <span class="text-[9px] font-bold text-text-muted mt-2 px-2 uppercase tracking-widest opacity-60" x-text="formatTime(message.timestamp)"></span>
                </div>
            </template>

            <div x-show="isTyping" class="flex items-start animate-pulse">
                <div class="bg-surface dark:bg-slate-800 px-5 py-4 rounded-[1.5rem] rounded-tl-none border border-slate-100 dark:border-slate-800">
                    <div class="flex gap-1.5">
                        <span class="w-1.5 h-1.5 bg-primary/40 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-primary/60 rounded-full animate-bounce delay-100"></span>
                        <span class="w-1.5 h-1.5 bg-primary/80 rounded-full animate-bounce delay-200"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-panel dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <form @submit.prevent="sendMessage" class="relative group">
                <input
                    type="text"
                    x-model="inputMessage"
                    @keydown.enter.prevent="sendMessage"
                    placeholder="Ask me anything..."
                    class="w-full bg-surface h-16 dark:bg-slate-800 border-slate-200 dark:border-slate-800 rounded-2xl py-4.5 pl-6 pr-14 text-sm font-medium focus:border-primary focus:ring-4 focus:ring-primary/10 dark:text-white placeholder:text-slate-400 transition-all outline-none"
                    :disabled="isTyping"
                    x-ref="inputField">
                <button
                    type="submit"
                    class="absolute right-2 top-2 w-11 h-11 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 flex items-center justify-center hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:scale-100"
                    :disabled="!inputMessage.trim() || isTyping">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
            <p class="text-[9px] text-center text-text-muted mt-4 font-bold uppercase tracking-[0.2em] opacity-50">CourseBook Intelligent Support</p>
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
                if (content.includes('<') && content.includes('>')) {
                    return content;
                }
                
                // Simple markdown-like formatting for plain text
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
