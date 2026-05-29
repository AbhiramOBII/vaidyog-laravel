<div>
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Assign Plan</h1>

    @if($successMessage)
    <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ $successMessage }}</div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-neutral-100 dark:bg-neutral-800 rounded-lg p-1">
        <button wire:click="$set('activeTab', 'job_seeker')" class="px-4 py-2 rounded-md text-sm font-medium {{ $activeTab === 'job_seeker' ? 'bg-white shadow-sm text-[#464d79]' : 'text-neutral-600' }}">Job Seeker</button>
        <button wire:click="$set('activeTab', 'recruiter')" class="px-4 py-2 rounded-md text-sm font-medium {{ $activeTab === 'recruiter' ? 'bg-white shadow-sm text-[#464d79]' : 'text-neutral-600' }}">Recruiter</button>
    </div>

    @if($activeTab === 'job_seeker')
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 max-w-xl space-y-4">
        <h2 class="text-sm font-semibold text-neutral-500 uppercase">Assign to Job Seeker</h2>
        <div>
            <label class="block text-sm font-medium mb-1">Search User</label>
            <input type="text" wire:model.live.debounce.300ms="jsUserSearch" class="w-full h-10 px-3 border rounded-lg text-sm" placeholder="Name or email..." />
            @if($this->jobSeekerUsers->isNotEmpty())
            <div class="mt-1 border rounded-lg max-h-40 overflow-y-auto">
                @foreach($this->jobSeekerUsers as $u)
                <button wire:click="$set('jsUserId', '{{ $u->id }}')" class="w-full px-3 py-2 text-left text-sm hover:bg-neutral-50 {{ $jsUserId === $u->id ? 'bg-[#464d79]/10 font-medium' : '' }}">{{ $u->name }} ({{ $u->email }})</button>
                @endforeach
            </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Select Plan</label>
            <select wire:model.live="jsPlanId" class="w-full h-10 px-3 border rounded-lg text-sm">
                <option value="">Choose plan...</option>
                @foreach($jsPlans as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->ranking->value }})</option>
                @endforeach
            </select>
        </div>
        @if($jsPlanId)
        <div>
            <label class="block text-sm font-medium mb-1">Select Option</label>
            <select wire:model="jsOptionId" class="w-full h-10 px-3 border rounded-lg text-sm">
                <option value="">Choose option...</option>
                @foreach($this->jsPlanOptions as $o)
                <option value="{{ $o->id }}">{{ $o->label }} — ₹{{ number_format($o->price) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-sm font-medium mb-1">Note (optional)</label>
            <input type="text" wire:model="jsNote" class="w-full h-10 px-3 border rounded-lg text-sm" placeholder="Reason..." />
        </div>
        <button wire:click="assignJobSeeker" class="h-10 px-6 bg-[#464d79] text-white rounded-lg text-sm font-medium">Assign Plan</button>
    </div>
    @else
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 max-w-xl space-y-4">
        <h2 class="text-sm font-semibold text-neutral-500 uppercase">Assign to Recruiter</h2>
        <div>
            <label class="block text-sm font-medium mb-1">Search Recruiter</label>
            <input type="text" wire:model.live.debounce.300ms="recUserSearch" class="w-full h-10 px-3 border rounded-lg text-sm" placeholder="Name or email..." />
            @if($this->recruiterUsers->isNotEmpty())
            <div class="mt-1 border rounded-lg max-h-40 overflow-y-auto">
                @foreach($this->recruiterUsers as $u)
                <button wire:click="$set('recUserId', '{{ $u->id }}')" class="w-full px-3 py-2 text-left text-sm hover:bg-neutral-50 {{ $recUserId === $u->id ? 'bg-[#464d79]/10 font-medium' : '' }}">{{ $u->name }} ({{ $u->email }})</button>
                @endforeach
            </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Select Plan</label>
            <select wire:model.live="recPlanId" class="w-full h-10 px-3 border rounded-lg text-sm">
                <option value="">Choose plan...</option>
                @foreach($recPlans as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->recruiter_type->label() }})</option>
                @endforeach
            </select>
        </div>
        @if($recPlanId)
        <div>
            <label class="block text-sm font-medium mb-1">Select Option</label>
            <select wire:model="recOptionId" class="w-full h-10 px-3 border rounded-lg text-sm">
                <option value="">Choose option...</option>
                @foreach($this->recPlanOptions as $o)
                <option value="{{ $o->id }}">{{ $o->label }} — ₹{{ number_format($o->price) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-sm font-medium mb-1">Note (optional)</label>
            <input type="text" wire:model="recNote" class="w-full h-10 px-3 border rounded-lg text-sm" placeholder="Reason..." />
        </div>
        <button wire:click="assignRecruiter" class="h-10 px-6 bg-[#464d79] text-white rounded-lg text-sm font-medium">Assign Plan</button>
    </div>
    @endif
</div>
