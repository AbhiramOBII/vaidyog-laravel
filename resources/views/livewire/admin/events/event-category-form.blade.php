<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $categoryId ? 'Edit' : 'Create' }} Event Category</h1>
        <a href="{{ route('admin.event-categories.index') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700">Back to List</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-5">
            {{-- Title & Slug --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Title *</label>
                    <input type="text" wire:model.live.debounce.300ms="title" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Slug *</label>
                    <input type="text" wire:model="slug" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Parent & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Parent Category</label>
                    <select wire:model="parent_id" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
                        <option value="">None (Top Level)</option>
                        @foreach ($parentCategories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Status *</label>
                    <select wire:model="status" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            {{-- Short Description --}}
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Short Description</label>
                <textarea wire:model="short_description" rows="2" maxlength="500" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none"></textarea>
            </div>

            {{-- Full Description (CKEditor) --}}
            <div wire:ignore>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Full Description</label>
                <div
                    x-data="{ editor: null }"
                    x-init="
                        ClassicEditor.create($refs.editor, { toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo'] })
                            .then(e => {
                                editor = e;
                                e.setData(@js($full_description));
                                e.model.document.on('change:data', () => { @this.set('full_description', e.getData()) });
                            })
                    "
                >
                    <div x-ref="editor"></div>
                </div>
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Thumbnail Image</label>
                @if ($existing_thumbnail && !$thumbnail_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($existing_thumbnail) }}" class="w-20 h-20 rounded-lg object-cover border">
                    </div>
                @endif
                @if ($thumbnail_image)
                    <div class="mb-2">
                        <img src="{{ $thumbnail_image->temporaryUrl() }}" class="w-20 h-20 rounded-lg object-cover border">
                    </div>
                @endif
                <input type="file" wire:model="thumbnail_image" accept="image/*" class="text-sm text-neutral-600">
                @error('thumbnail_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-5">
            <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">SEO Settings</h3>
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Meta Title</label>
                <input type="text" wire:model="meta_title" maxlength="200" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Meta Description</label>
                <textarea wire:model="meta_description" rows="2" maxlength="500" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.event-categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">{{ $categoryId ? 'Update Category' : 'Create Category' }}</button>
        </div>
    </form>
</div>
