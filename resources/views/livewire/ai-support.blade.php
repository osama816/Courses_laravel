@auth
<div class="fixed bottom-6 right-6 z-[100]">
    <!-- Floating Button -->
    <button
        wire:click="toggleChat"
        class="w-16 h-16 rounded-2xl bg-primary text-white flex items-center justify-center shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all duration-300 group"
        aria-label="Open AI Support">
        @if(!$isOpen)
            <img src="{{ asset('assets/chat.png') }}" alt="Chat" class="w-8 h-8 group-hover:animate-bounce">
        @else
            <i class="fa-solid fa-xmark text-2xl rotate-90 transition-transform"></i>
        @endif
    </button>

    <!-- Chat Widget -->
    @if($isOpen)
    <div 
        class="absolute bottom-20 right-0 w-[400px] max-w-[calc(100vw-48px)] h-[600px] max-h-[calc(100vh-120px)] bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-3xl border border-slate-200/50 dark:border-slate-800/50 flex flex-col overflow-hidden backdrop-blur-xl animate-in fade-in slide-in-from-bottom-5 duration-300">
        
        <!-- Header -->
        <div class="p-6 bg-slate-900 text-white flex justify-between items-center relative overflow-hidden">
            <!-- Animation Background -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
            
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/10 p-2">
                    <img src="{{ asset('assets/chat.png') }}" alt="AI" class="w-full h-full object-contain">
                </div>
                <div>
                    <h6 class="font-bold text-lg leading-tight uppercase tracking-tight">AI Assistant</h6>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $isTyping ? 'animate-pulse' : '' }}"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $isTyping ? 'Thinking...' : 'Online Now' }}</span>
                    </div>
                </div>
            </div>
            <div class="relative flex items-center gap-1">
                <button wire:click="clearChat" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-slate-400 hover:text-white transition-all" title="Clear History">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
                <button wire:click="toggleChat" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-slate-400 hover:text-white transition-all" aria-label="Close">
                    <i class="fa-solid fa-minus text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="flex-grow overflow-y-auto px-6 py-8 space-y-6 scrollbar-none" 
            x-data 
            x-init="$el.scrollTop = $el.scrollHeight"
            @scroll-to-bottom.window="$el.scrollTop = $el.scrollHeight">
            
            @if(count($messages) === 0)
            <!-- Welcome Message -->
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
                    <button wire:click="sendQuickMessage('What courses do I have?')" class="group flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-200/50 dark:border-slate-800 hover:border-primary hover:bg-primary/5 transition-all text-left">
                        <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><i class="fa-solid fa-book-bookmark text-primary group-hover:scale-110 transition-transform"></i></span>
                        <span class="text-sm font-bold text-text-base">My Enrolled Courses</span>
                    </button>
                    <button wire:click="sendQuickMessage('Check my payment status')" class="group flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-200/50 dark:border-slate-800 hover:border-primary hover:bg-primary/5 transition-all text-left">
                        <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><i class="fa-solid fa-receipt text-primary group-hover:scale-110 transition-transform"></i></span>
                        <span class="text-sm font-bold text-text-base">Payment Records</span>
                    </button>
                </div>
            </div>
            @else
            <!-- Messages -->
            @foreach($messages as $index => $message)
            <div class="flex flex-col animate-in fade-in slide-in-from-bottom-2 duration-300 {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}"
                wire:key="message-{{ $index }}">
                <div class="max-w-[85%] px-5 py-3.5 rounded-[1.5rem] text-sm leading-relaxed shadow-sm {{ $message['role'] === 'user' ? 'bg-primary text-white rounded-br-none' : 'bg-surface dark:bg-slate-800 text-text-base rounded-tl-none border border-slate-100 dark:border-slate-800' }}">
                    {!! $this->formatMessage($message['content']) !!}
                </div>
                <span class="text-[9px] font-bold text-text-muted mt-2 px-2 uppercase tracking-widest opacity-60">
                    {{ $this->formatTime($message['timestamp']) }}
                </span>
            </div>
            @endforeach

            <!-- Typing Indicator -->
            @if($isTyping)
            <div class="flex items-start animate-pulse">
                <div class="bg-surface dark:bg-slate-800 px-5 py-4 rounded-[1.5rem] rounded-tl-none border border-slate-100 dark:border-slate-800">
                    <div class="flex gap-1.5">
                        <span class="w-1.5 h-1.5 bg-primary/40 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-primary/60 rounded-full animate-bounce delay-100"></span>
                        <span class="w-1.5 h-1.5 bg-primary/80 rounded-full animate-bounce delay-200"></span>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-panel dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <form wire:submit.prevent="sendMessage" class="relative group">
                <input
                    type="text"
                    wire:model="inputMessage"
                    placeholder="Ask me anything..."
                    class="w-full bg-surface dark:bg-slate-800 border-slate-200 dark:border-slate-800 rounded-2xl py-4.5 pl-6 pr-14 text-sm font-medium focus:border-primary focus:ring-4 focus:ring-primary/10 dark:text-white placeholder:text-slate-400 transition-all outline-none"
                    @disabled($isTyping)
                    autofocus>

                <button
                    type="submit"
                    class="absolute right-2 top-2 w-11 h-11 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 flex items-center justify-center hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:scale-100"
                    wire:loading.attr="disabled"
                    @disabled(!trim($inputMessage) || $isTyping)>
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
            <p class="text-[9px] text-center text-text-muted mt-4 font-bold uppercase tracking-[0.2em] opacity-50">CourseBook Intelligent Support</p>
        </div>
    </div>
    @endif
</div>
@endauth
</div>
