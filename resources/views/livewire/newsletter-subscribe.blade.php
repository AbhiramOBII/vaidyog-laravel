<div>
    @if($success)
        <div class="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-teal-500/20 border border-teal-400/30">
            <svg class="w-5 h-5 text-teal-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            <span class="text-sm text-teal-300 font-medium">{{ $message }}</span>
        </div>
    @else
        <form wire:submit="subscribe" class="flex gap-2 w-full md:w-auto">
            <input wire:model="email" type="email" placeholder="Enter your email" class="flex-1 md:w-72 px-4 py-2.5 rounded-lg bg-white/10 border border-white/10 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-400/40 focus:border-teal-400"/>
            <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white shrink-0" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">
                <span wire:loading.remove wire:target="subscribe">Subscribe</span>
                <span wire:loading wire:target="subscribe">...</span>
            </button>
        </form>
        @error('email')
            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
        @enderror
    @endif
</div>
