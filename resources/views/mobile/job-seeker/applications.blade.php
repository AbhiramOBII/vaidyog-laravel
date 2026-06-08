@extends('mobile.layout')
@section('title', 'My Applications')

@section('app_header')
<div class="bg-white px-5 py-3.5 flex items-center justify-between border-b border-neutral-100 shrink-0">
    <p class="text-lg font-bold text-neutral-900">My Applications</p>
    <a href="{{ route('mobile.jobseeker.jobs') }}" class="flex items-center gap-1.5 text-xs font-semibold text-white px-3 py-2 rounded-xl" style="background:linear-gradient(135deg,#464d79,#4ab098)">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Browse
    </a>
</div>
@endsection

@section('content')
<div class="px-4 pt-4 space-y-4 pb-2">

    {{-- Stats row --}}
    <div class="grid grid-cols-4 gap-2">
        @foreach([
            ['12','Total','bg-[#464d79]/10 text-[#464d79]'],
            ['4','Reviewed','bg-indigo-100 text-indigo-600'],
            ['2','Interviews','bg-amber-100 text-amber-600'],
            ['1','Offered','bg-green-100 text-green-600'],
        ] as [$n,$lbl,$cls])
        <div class="bg-white rounded-2xl p-3 border border-neutral-100 text-center">
            <p class="text-lg font-bold {{ explode(' ',$cls)[1] }}">{{ $n }}</p>
            <p class="text-[10px] text-neutral-500 mt-0.5 leading-tight">{{ $lbl }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filter tabs --}}
    <div class="flex gap-2 overflow-x-auto hide-scroll pb-0.5">
        @foreach(['All (12)','Applied','Under Review','Shortlisted','Interviewed','Offered'] as $i => $tab)
        <button class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $i === 0 ? 'bg-[#464d79] text-white' : 'bg-white text-neutral-500 border border-neutral-200' }}">{{ $tab }}</button>
        @endforeach
    </div>

    {{-- Application Cards --}}
    @foreach([
        ['Senior Cardiologist','Apollo Hospitals, Chennai','shortlisted','Shortlisted','bg-blue-100 text-blue-700','Applied 2 weeks ago',3,false],
        ['Cardiac ICU Specialist','Narayana Health, Bengaluru','reviewed','Under Review','bg-indigo-100 text-indigo-600','Applied 3 weeks ago',2,false],
        ['Cardiology Consultant','AIIMS Delhi','applied','Applied','bg-neutral-100 text-neutral-600','Applied 1 week ago',1,false],
        ['Senior Physician','Fortis Healthcare, Mumbai','offered','Offered!','bg-green-100 text-green-700','Applied 2 months ago',5,true],
        ['Cardiac Surgeon','Manipal Hospitals, Pune','interviewed','Interviewed','bg-amber-100 text-amber-700','Applied 1 month ago',4,false],
    ] as [$title,$institution,$status,$statusLabel,$statusCls,$timeAgo,$step,$isOffer])
    @php
        $steps = ['applied','reviewed','shortlisted','interviewed','offered'];
        $idx = array_search($status, $steps);
        $isRejected = $status === 'rejected';
    @endphp
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#464d79]/10 to-[#4ab098]/10 flex items-center justify-center shrink-0 border border-neutral-100">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-neutral-900 truncate">{{ $title }}</p>
                            <p class="text-xs text-neutral-500 mt-0.5 truncate">{{ $institution }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-1 rounded-full font-semibold shrink-0 {{ $statusCls }}">{{ $statusLabel }}</span>
                    </div>
                    <p class="text-[11px] text-neutral-400 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $timeAgo }}
                    </p>
                </div>
            </div>

            {{-- Progress --}}
            <div class="mt-3.5">
                <div class="flex items-center gap-1 mb-1.5">
                    @foreach($steps as $i => $s)
                    <div class="flex-1 h-1.5 rounded-full transition-colors {{ $isRejected ? 'bg-red-200' : ($i <= $idx ? 'bg-[#4ab098]' : 'bg-neutral-100') }}"></div>
                    @endforeach
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] text-neutral-400">Applied</span>
                    <span class="text-[10px] {{ $isOffer ? 'text-green-600 font-semibold' : 'text-neutral-400' }}">{{ $isOffer ? '🎉 Offered!' : 'Offered' }}</span>
                </div>
            </div>
        </div>
        @if($isOffer)
        <div class="px-4 pb-4">
            <button class="w-full py-2 rounded-xl text-xs font-semibold text-white" style="background:linear-gradient(135deg,#10b981,#059669)">View Offer Letter</button>
        </div>
        @endif
    </div>
    @endforeach

</div>
@endsection
