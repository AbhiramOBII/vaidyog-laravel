<div class="w-full max-w-md mx-auto">
    <div class="text-center mb-8">
        <span class="text-2xl font-bold text-[#464d79]">Vaidyog</span>
        <p class="text-sm text-neutral-500 mt-1">Complete your registration</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 sm:p-8">
        <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 border border-green-200 mb-6">
            <svg class="w-5 h-5 text-green-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-medium text-green-800">Google verified</p>
                <p class="text-xs text-green-600">{{ $email }}</p>
            </div>
        </div>

        <h2 class="text-lg font-bold text-neutral-900 mb-1">Tell us more</h2>
        <p class="text-sm text-neutral-500 mb-6">We need a few details to set up your institution.</p>

        <form wire:submit="complete" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution Name <span class="text-red-500">*</span></label>
                <input wire:model="institutionName" type="text" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('institutionName') border-red-400 @enderror"/>
                @error('institutionName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution Type <span class="text-red-500">*</span></label>
                <select wire:model="medType" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('medType') border-red-400 @enderror">
                    <option value="">Select...</option>
                    @foreach($medTypes as $mt)<option value="{{ $mt->value }}">{{ $mt->label() }}</option>@endforeach
                </select>
                @error('medType') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Contact Person <span class="text-red-500">*</span></label>
                <input wire:model="contactPersonName" type="text" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                @error('contactPersonName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Phone <span class="text-neutral-400">(optional)</span></label>
                <input wire:model="phone" type="tel" placeholder="9876543210" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            </div>
            <button type="submit" class="w-full h-11 bg-[#464d79] hover:bg-[#3a4169] text-white font-semibold rounded-xl transition-colors">Complete Registration</button>
        </form>
    </div>
</div>
