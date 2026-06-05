<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Sub-Admins</h1>
            <p class="text-sm text-slate-500 mt-1">Manage sub-admin accounts and their roles</p>
        </div>
        @if(auth('admin')->user()->hasPermission('sub_admins.create'))
        <a href="{{ route('admin.sub-admins.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 h-10 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Sub-Admin
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name or email..." class="w-full sm:w-72 h-9 px-3 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent">
        </div>

        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/50">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Name</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Email</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Role</th>
                    <th class="text-center px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Last Login</th>
                    <th class="text-right px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($subAdmins as $admin)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 text-xs font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $admin->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $admin->email }}</td>
                    <td class="px-4 py-3">
                        @if($admin->role)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400">{{ $admin->role->name }}</span>
                        @else
                        <span class="text-xs text-slate-400">No role</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if(auth('admin')->user()->hasPermission('sub_admins.edit'))
                        <button wire:click="toggleActive('{{ $admin->id }}')" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors {{ $admin->is_active ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </button>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $admin->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">{{ $admin->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if(auth('admin')->user()->hasPermission('sub_admins.edit'))
                            <a href="{{ route('admin.sub-admins.edit', $admin->id) }}" wire:navigate class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                            </a>
                            @endif
                            @if(auth('admin')->user()->hasPermission('sub_admins.delete'))
                            <button wire:click="delete('{{ $admin->id }}')" wire:confirm="Delete this sub-admin?" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">No sub-admins found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($subAdmins->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">{{ $subAdmins->links() }}</div>
        @endif
    </div>
</div>
