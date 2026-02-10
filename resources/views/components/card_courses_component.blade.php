@props(['course'])

<div class="group">
    <div class="premium-card h-full flex flex-col overflow-hidden animate-fade-in-up">
        <!-- Thumbnail -->
        <a href="{{ route('courses.show', $course->id) }}" class="relative block overflow-hidden aspect-video">
            @if($course->image_url)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($course->image_url) }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                     alt="{{ $course->getTranslation('title', app()->getLocale()) }}">
            @else
                <img src="{{ asset('assets/img/defaults/course-placeholder.jpg') }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                     alt="{{ $course->getTranslation('title', app()->getLocale()) }}">
            @endif
            
            <!-- Price Overlay -->
            <div class="absolute top-4 right-4 bg-panel/90 backdrop-blur-md px-3 py-1.5 rounded-lg shadow-sm">
                <span class="text-primary font-bold text-lg">${{ number_format($course->price, 2) }}</span>
            </div>

            <!-- Level Badge -->
            <div class="absolute top-4 left-4 bg-slate-900/60 backdrop-blur-md px-2 py-1 rounded text-[10px] font-bold text-white uppercase tracking-wider">
                {{ $course->level }}
            </div>
        </a>

        <!-- Content -->
        <div class="p-6 flex flex-col flex-grow">
            <h3 class="text-xl font-bold text-text-base mb-2 line-clamp-1 group-hover:text-primary transition-colors">
                <a href="{{ route('courses.show', $course->id) }}">{{ $course->getTranslation('title', app()->getLocale()) }}</a>
            </h3>

            <div class="flex items-center gap-2 mb-4">
                <div class="flex items-center gap-1 text-amber-400">
                    @php
                        $rating = $course->rating ?? 0;
                        $fullStars = floor($rating);
                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                    @endphp
                    @for($i = 0; $i < $fullStars; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                    @if($hasHalfStar)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @endif
                    @for($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                        <i class="fa-regular fa-star"></i>
                    @endfor
                </div>
                <span class="text-xs font-semibold text-text-muted">({{ number_format($rating, 1) }})</span>
                <span class="text-slate-300 mx-1">•</span>
                <span class="text-xs font-medium text-text-muted">{{ $course->instructor->user->name ?? 'Instructor' }}</span>
            </div>

            <p class="text-text-muted text-sm mb-6 line-clamp-2 leading-relaxed">
                {{ Str::limit($course->description, 100) }}
            </p>

            <!-- Metadata & Action -->
            <div class="mt-auto pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="flex items-center gap-2 text-xs font-bold text-text-muted">
                    <i class="fa-solid fa-clock text-primary"></i>
                    {{ $course->duration ?? '10' }} {{ __('nav.hours') }}
                </span>
                
                <a href="{{ route('courses.show', $course->id) }}" 
                   class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-surface text-text-muted hover:bg-primary hover:text-white transition-all">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
