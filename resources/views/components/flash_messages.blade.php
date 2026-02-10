@if (session()->has('success') || session()->has('error') || session()->has('info') || session()->has('warning') || (isset($errors) && $errors->any()))
    <div x-data="{ 
            show: true,
            init() {
                setTimeout(() => this.show = false, 5000);
            }
         }" 
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed top-24 right-4 z-9999 max-w-sm w-full pointer-events-none">
        
        @php
            $type = 'info';
            if(session()->has('success')) $type = 'success';
            elseif(session()->has('error') || (isset($errors) && $errors->any())) $type = 'error';
            elseif(session()->has('warning')) $type = 'warning';
            elseif(session()->has('info')) $type = 'info';

            $config = [
                'success' => ['bg' => 'bg-emerald-50/90 dark:bg-emerald-900/40', 'border' => 'border-emerald-200 dark:border-emerald-800', 'text' => 'text-emerald-800 dark:text-emerald-300', 'icon' => 'bi-check-circle-fill'],
                'error' => ['bg' => 'bg-rose-50/90 dark:bg-rose-900/40', 'border' => 'border-rose-200 dark:border-rose-800', 'text' => 'text-rose-800 dark:text-rose-300', 'icon' => 'bi-exclamation-octagon-fill'],
                'warning' => ['bg' => 'bg-amber-50/90 dark:bg-amber-900/40', 'border' => 'border-amber-200 dark:border-amber-800', 'text' => 'text-amber-800 dark:text-amber-300', 'icon' => 'bi-exclamation-triangle-fill'],
                'info' => ['bg' => 'bg-sky-50/90 dark:bg-sky-900/40', 'border' => 'border-sky-200 dark:border-sky-800', 'text' => 'text-sky-800 dark:text-sky-300', 'icon' => 'bi-info-circle-fill'],
            ];
            $style = $config[$type];
        @endphp

        <div class="flex items-start p-4 rounded-2xl border {{ $style['bg'] }} {{ $style['border'] }} shadow-2xl backdrop-blur-md pointer-events-auto">
            <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $style['bg'] }} brightness-90">
                <i class="bi {{ $style['icon'] }} text-xl {{ $style['text'] }}"></i>
            </div>
            <div class="ml-4 flex-1">
                @if(isset($errors) && $errors->any())
                    <p class="text-sm font-bold {{ $style['text'] }} mb-1">{{ __('messages.validation_errors') }}</p>
                    <ul class="text-xs {{ $style['text'] }} opacity-80 list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm font-bold {{ $style['text'] }}">
                        {{ session($type) }}
                    </p>
                @endif
            </div>
            <button @click="show = false" class="ml-4 shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
@endif
