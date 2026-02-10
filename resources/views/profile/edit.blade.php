@extends('layouts.app_wep')

@section('title', __('Profile') . ' · CourseBook')

@section('content')
<main class="py-12 bg-slate-50/50 dark:bg-slate-950/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                    <i class="fa-solid fa-user-gear text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ __('Profile Settings') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">
                        {{ __('Manage your account information and preferences') }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:border-primary transition-all duration-300 shadow-sm">
                    {{ __('Back to Home') }}
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row flex-wrap gap-8 items-start">
            <!-- Left Column: Settings Navigation / Sidebar -->
            <div class="w-full lg:w-[320px] shrink-0 space-y-6">
                <div class="p-8 bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/50 dark:border-slate-800/50">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 space-y-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-2">Account Statistics</p>
                        <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Total Bookings</span>
                            <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary font-bold text-sm">{{ $user->bookings->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Content Cards -->
            <div class="flex-1 min-w-[min(100%,700px)] flex flex-wrap gap-8 items-start">
                
                <!-- Profile Information -->
                <div class="flex-1 min-w-[min(100%,480px)] p-8 bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/50 dark:border-slate-800/50 scroll-mt-24" id="info">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="flex-1 min-w-[min(100%,480px)] p-8 bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/50 dark:border-slate-800/50 scroll-mt-24" id="password">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- My Bookings Summary -->
                <div class="w-full p-8 bg-panel dark:bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/50 dark:border-slate-800/50">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-primary"></i>
                            {{ __('My Bookings') }}
                        </h2>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-bold text-primary hover:text-primary-hover transition-colors">
                            {{ __('View All') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    
                    <div class="overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">{{ __('Course') }}</th>
                                    <th class="px-6 py-4 text-center">{{ __('Status') }}</th>
                                    <th class="px-6 py-4 text-end">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($user->bookings->take(5) as $booking)
                                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $booking->course->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusColors = [
                                                    'confirmed' => 'bg-emerald-500/10 text-emerald-600',
                                                    'pending' => 'bg-amber-500/10 text-amber-600',
                                                    'cancelled' => 'bg-rose-500/10 text-rose-600'
                                                ];
                                                $colorClass = $statusColors[$booking->status] ?? 'bg-slate-500/10 text-slate-600';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $colorClass }}">
                                                {{ __($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-end text-sm text-slate-500 dark:text-slate-400 tabular-nums">
                                            {{ $booking->created_at->format('Y-m-d') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="text-slate-400 mb-2 text-3xl">
                                                <i class="fa-solid fa-folder-open opacity-20"></i>
                                            </div>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No bookings found.') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Danger Zone (Optional - comment removed for cleaner look if needed) -->
                {{-- 
                <div class="w-full p-8 bg-rose-50 dark:bg-rose-950/20 rounded-[2.5rem] shadow-xl border border-rose-200/50 dark:border-rose-900/30">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h2 class="text-xl font-bold text-rose-900 dark:text-rose-100">{{ __('Danger Zone') }}</h2>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div> 
                --}}

            </div>
        </div>
        </div>
    </div>
</main>
@endsection

