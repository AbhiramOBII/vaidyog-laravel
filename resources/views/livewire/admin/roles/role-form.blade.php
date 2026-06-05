<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $roleId ? 'Edit Role' : 'Create Role' }}</h1>
            <p class="text-sm text-slate-500 mt-1">Define permissions for this role</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" wire:navigate class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">← Back to Roles</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Basic Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Role Name *</label>
                    <input wire:model="name" type="text" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="e.g. Content Manager">
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
                    <input wire:model="description" type="text" class="w-full h-10 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500" placeholder="Brief description of this role">
                    @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">Permissions</h2>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="selectAll" class="text-xs font-medium text-brand-600 hover:text-brand-700">Select All</button>
                    <span class="text-slate-300">|</span>
                    <button type="button" wire:click="deselectAll" class="text-xs font-medium text-slate-500 hover:text-slate-700">Deselect All</button>
                </div>
            </div>
            @error('selectedPermissions') <div class="text-xs text-red-500">{{ $message }}</div> @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($allPermissions as $group => $permissions)
                <div class="space-y-2">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2">{{ ucfirst($group) }}</h3>
                    <div class="space-y-1.5">
                        @foreach($permissions as $key => $label)
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="checkbox"
                                wire:click="togglePermission('{{ $key }}')"
                                @checked(in_array($key, $selectedPermissions))
                                class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-brand-600 focus:ring-brand-500">
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                <p class="text-xs text-slate-400">Selected: <span class="font-semibold text-slate-600 dark:text-slate-300">{{ count($selectedPermissions) }}</span> permissions</p>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}" wire:navigate class="px-5 h-10 inline-flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            <button type="submit" class="px-5 h-10 inline-flex items-center text-sm font-semibold text-white bg-brand-600 rounded-xl hover:bg-brand-700 transition-colors">
                {{ $roleId ? 'Update Role' : 'Create Role' }}
            </button>
        </div>
    </form>
</div>
