{{-- Shared recognition list partial --}}
@if ($items->count())
    <div class="space-y-2 mb-3">
        @foreach ($items as $item)
            <div class="flex items-start justify-between p-3 bg-white rounded-lg border border-neutral-100">
                <div>
                    <h5 class="text-sm font-medium text-neutral-900">{{ $item->title ?? $item->organization_name }}</h5>
                    @if (isset($item->publication_name) && $item->publication_name)<p class="text-xs text-neutral-500">{{ $item->publication_name }}</p>@endif
                    @if (isset($item->event_name) && $item->event_name)<p class="text-xs text-neutral-500">{{ $item->event_name }}</p>@endif
                    @if (isset($item->institution) && $item->institution)<p class="text-xs text-neutral-500">{{ $item->institution }}</p>@endif
                    @if (isset($item->issuing_body) && $item->issuing_body)<p class="text-xs text-neutral-500">{{ $item->issuing_body }}</p>@endif
                    @if (isset($item->role) && $item->role)<p class="text-xs text-neutral-500">{{ $item->role }}</p>@endif
                    @php $date = $item->published_date ?? $item->event_date ?? $item->award_date ?? $item->member_since ?? null; @endphp
                    @if ($date)<p class="text-xs text-neutral-400 mt-0.5">{{ $date->format('M Y') }}</p>@endif
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="openForm({{ $item->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                    <button wire:click="delete({{ $item->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:underline">Delete</button>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-xs text-neutral-400 italic mb-3">None added yet.</p>
@endif
