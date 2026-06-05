<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $adminId ? 'Edit Sub-Admin' : 'Add Sub-Admin' }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $adminId ? 'Update sub-admin details' : 'Create a new sub-admin account' }}</p>
        </div>
        <a href="{{ route('admin.sub-admins.index') }}" wire:navigate class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">← Back</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-5">

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full Name *</label>
                <input wire:model="name" type="text" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="John Doe">
                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email *</label>
                <input wire:model="email" type="email" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="admin@example.com">
                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Role *</label>
                <select wire:model="admin_role_id" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500">
                    <option value="">Select a role...</option>
                    @foreach($roles as $id => $roleName)
                    <option value="{{ $id }}">{{ $roleName }}</option>
                    @endforeach
                </select>
                @error('admin_role_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password {{ $adminId ? '(leave blank to keep)' : '*' }}</label>
                    <input wire:model="password" type="password" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="••••••••">
                    @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm Password</label>
                    <input wire:model="password_confirmation" type="password" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input wire:model="is_active" type="checkbox" class="sr-only peer">
                    <div class="w-9 h-5 bg-slate-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-600"></div>
                </label>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Account Active</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.sub-admins.index') }}" wire:navigate class="px-5 h-10 inline-flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            <button type="submit" class="px-5 h-10 inline-flex items-center text-sm font-semibold text-white bg-brand-600 rounded-xl hover:bg-brand-700 transition-colors">
                {{ $adminId ? 'Update Sub-Admin' : 'Create Sub-Admin' }}
            </button>
        </div>
    </form>
</div>
