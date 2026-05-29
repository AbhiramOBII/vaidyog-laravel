<div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Send Feedback</h2>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    <form wire:submit="submit" class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-4">
        <div>
            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Your Feedback *</label>
            <textarea wire:model="feedback" rows="5" maxlength="2000" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none" placeholder="Share your feedback, suggestions, or experience..."></textarea>
            @error('feedback') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Submit Feedback</button>
        </div>
    </form>
</div>
