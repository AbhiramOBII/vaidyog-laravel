<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Manage FAQs</h1>
        <button wire:click="addMore" type="button" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">+ Add More</button>
    </div>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="space-y-4">
        @foreach ($faqs as $index => $faq)
            <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-5 relative" wire:key="faq-{{ $faq['id'] ?? $index }}">
                {{-- Header with index and actions --}}
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wide">FAQ #{{ $index + 1 }}</span>
                    <div class="flex items-center gap-1">
                        @if ($index > 0)
                            <button type="button" wire:click="moveUp({{ $index }})" class="p-1.5 text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors" title="Move Up">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        @endif
                        @if ($index < count($faqs) - 1)
                            <button type="button" wire:click="moveDown({{ $index }})" class="p-1.5 text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors" title="Move Down">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        @endif
                        @if (count($faqs) > 1)
                            <button type="button" wire:click="remove({{ $index }})" wire:confirm="Remove this FAQ?" class="p-1.5 text-red-400 hover:text-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Question --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Question *</label>
                    <input type="text" wire:model="faqs.{{ $index }}.question" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white" placeholder="Enter the question...">
                    @error("faqs.{$index}.question") <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Answer --}}
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Answer *</label>
                    <textarea wire:model="faqs.{{ $index }}.answer" rows="3" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none" placeholder="Enter the answer..."></textarea>
                    @error("faqs.{$index}.answer") <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endforeach

        {{-- Add More (bottom) & Save --}}
        <div class="flex items-center justify-between pt-2">
            <button type="button" wire:click="addMore" class="px-4 py-2 text-sm font-medium text-[#464d79] border border-[#464d79]/30 rounded-lg hover:bg-[#464d79]/10 transition-colors">+ Add Another FAQ</button>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Save All FAQs</button>
        </div>
    </form>

    {{-- JSON Preview --}}
    @if (count($faqs) > 0 && $faqs[0]['question'])
        <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-xl border border-neutral-200 dark:border-neutral-800 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wide mb-3">JSON Output Preview</h3>
            <pre class="text-xs text-neutral-700 dark:text-neutral-300 overflow-x-auto whitespace-pre-wrap break-words bg-white dark:bg-neutral-900 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">{{ json_encode(collect($faqs)->map(fn($f, $i) => ['id' => $i + 1, 'question' => $f['question'], 'answer' => $f['answer']])->filter(fn($f) => $f['question'])->values(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif
</div>
