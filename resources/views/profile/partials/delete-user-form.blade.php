<section class="space-y-6">
    <header>
        <p class="text-sm font-medium text-rose-800 dark:text-rose-300">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3.5 rounded-premium bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-600/20 hover:bg-rose-700 active:scale-95 transition-all outline-none"
    >
        <i class="fa-solid fa-trash-can me-2"></i>
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-panel dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-4 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                {{ __('Are you sure?') }}
            </h2>

            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="space-y-2 mb-8">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full h-14"
                    placeholder="{{ __('Verify your password...') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-50 dark:border-slate-800">
                <button type="button" x-on:click="$dispatch('close')" class="px-8 py-3.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-8 py-3.5 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 shadow-xl shadow-rose-600/20 transition-all">
                    {{ __('Permanently Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
