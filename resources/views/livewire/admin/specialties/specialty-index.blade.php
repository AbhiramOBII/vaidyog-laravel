<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Specialties</h1>
        <button wire:click="create" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + Add Specialty
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Icon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Search Term</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Featured</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($specialties as $specialty)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $specialty->sort_order }}</td>
                    <td class="px-4 py-3">
                        @if($specialty->icon_path)
                            <img src="{{ asset('storage/' . $specialty->icon_path) }}" alt="{{ $specialty->name }}" class="w-10 h-10 object-contain"/>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $specialty->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $specialty->search_term }}</td>
                    <td class="px-4 py-3">
                        <button wire:click="toggleActive({{ $specialty->id }})" class="px-2 py-1 rounded-full text-xs font-medium {{ $specialty->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $specialty->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="px-4 py-3">
                        <button wire:click="toggleFeatured({{ $specialty->id }})" class="px-2 py-1 rounded-full text-xs font-medium {{ $specialty->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $specialty->is_featured ? '★ Featured' : '—' }}
                        </button>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="edit({{ $specialty->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mr-3">Edit</button>
                        <button wire:click="delete({{ $specialty->id }})" wire:confirm="Delete '{{ $specialty->name }}'?" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">No specialties yet. Click "Add Specialty" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($specialties->hasPages())
    <div class="mt-4">{{ $specialties->links() }}</div>
    @endif

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $editingId ? 'Edit' : 'Add' }} Specialty</h2>

            <form wire:submit="save" class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="e.g. Doctors & Physicians"/>
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Search Term --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Term</label>
                    <input type="text" wire:model="search_term" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="e.g. doctor (used for job search)"/>
                    @error('search_term') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Term used to filter jobs when this specialty is clicked.</p>
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (SVG/PNG/WebP) *</label>
                    @if($currentIcon)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('storage/' . $currentIcon) }}" alt="Current icon" class="w-12 h-12 object-contain border border-gray-200 rounded-lg p-1"/>
                        <span class="text-xs text-gray-500">Current icon</span>
                    </div>
                    @endif
                    <input type="file" wire:model="icon" accept=".svg,.png,.webp,.jpg,.jpeg" class="text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"/>
                    <div wire:loading wire:target="icon" class="text-xs text-indigo-600 mt-1">Uploading...</div>
                    @error('icon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" wire:model="sort_order" min="0" class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                    @error('sort_order') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Active --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                    <label for="is_active" class="text-sm text-gray-700">Active (visible on homepage)</label>
                </div>

                {{-- Featured --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_featured" id="is_featured" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"/>
                    <label for="is_featured" class="text-sm text-gray-700">Featured (highlighted on homepage)</label>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
