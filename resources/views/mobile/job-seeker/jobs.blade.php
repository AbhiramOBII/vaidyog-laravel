@extends('mobile.layout')
@section('title', 'Browse Jobs')

@section('app_header')
<div class="bg-white px-4 pt-3 pb-3 border-b border-neutral-100 shrink-0 space-y-3">
    <div class="flex items-center justify-between">
        <p class="text-lg font-bold text-neutral-900">Find Jobs</p>
        <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-neutral-100">
            <svg class="w-4.5 h-4.5 text-neutral-600 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        </button>
    </div>
    {{-- Search --}}
    <div class="relative">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Job title, specialty, hospital..."
               class="w-full pl-10 pr-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10 transition-colors"/>
    </div>
    {{-- Category chips --}}
    <div class="flex gap-2 overflow-x-auto hide-scroll pb-0.5">
        @foreach(['All','Doctors','Nurses','Pharmacy','Dentist','Physiotherapy','Lab'] as $i => $cat)
        <button class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $i === 0 ? 'bg-[#464d79] text-white' : 'bg-neutral-100 text-neutral-600' }}">{{ $cat }}</button>
        @endforeach
    </div>
</div>
@endsection

@section('content')
<div class="px-4 pt-4 space-y-3 pb-2">

    {{-- Results meta --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-neutral-500"><span class="font-bold text-neutral-900">247</span> jobs found</p>
        <button class="flex items-center gap-1.5 text-xs text-neutral-500 bg-white border border-neutral-200 px-3 py-1.5 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
            Latest
        </button>
    </div>

    {{-- Job Cards --}}
    @foreach([
        ['Senior Cardiologist','Apollo Hospitals','Chennai, Tamil Nadu','₹3–5L/mo','Full Time','Cardiologist','5–15 yrs','Featured','bg-green-100 text-green-700','2d ago',true],
        ['Cardiac ICU Specialist','Narayana Health','Bengaluru, Karnataka','₹2.5–4L/mo','Full Time','Cardiology','3–10 yrs','Remote OK','bg-blue-100 text-blue-700','5d ago',false],
        ['Interventional Cardiologist','Fortis Healthcare','Mumbai, Maharashtra','₹4–7L/mo','Full Time','Cardiology','8–20 yrs','Urgent','bg-red-100 text-red-700','1d ago',false],
        ['Cardiology Consultant','AIIMS Delhi','Delhi','₹2–3.5L/mo','Permanent','Cardiology','5–12 yrs','Govt','bg-purple-100 text-purple-700','3d ago',true],
        ['Pediatric Cardiologist','Manipal Hospitals','Pune, Maharashtra','₹2.5–4.5L/mo','Full Time','Cardiology','3–8 yrs','','','1w ago',false],
    ] as [$title,$hospital,$location,$salary,$type,$specialty,$exp,$badge,$badgeCls,$posted,$saved])
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#464d79]/10 to-[#4ab098]/10 flex items-center justify-center shrink-0 border border-neutral-100">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-neutral-900 leading-snug">{{ $title }}</p>
                            <p class="text-xs text-[#464d79] font-medium mt-0.5">{{ $hospital }}</p>
                        </div>
                        <button class="shrink-0 p-1">
                            <svg class="w-4.5 h-4.5 w-5 h-5 {{ $saved ? 'text-[#464d79] fill-[#464d79]' : 'text-neutral-300' }}" fill="{{ $saved ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-1 mt-2 flex-wrap">
                        <span class="text-[11px] text-neutral-500 flex items-center gap-0.5">
                            <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            {{ $location }}
                        </span>
                        @if($badge)
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $badgeCls }}">{{ $badge }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-neutral-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-neutral-100 text-neutral-600 px-2.5 py-1 rounded-lg font-medium">{{ $type }}</span>
                    <span class="text-xs bg-neutral-100 text-neutral-600 px-2.5 py-1 rounded-lg font-medium">{{ $exp }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-[#464d79]">{{ $salary }}</span>
                    <span class="text-[10px] text-neutral-400">{{ $posted }}</span>
                </div>
            </div>
        </div>
        <div class="px-4 pb-4">
            <button class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-all active:opacity-80" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                Apply Now
            </button>
        </div>
    </div>
    @endforeach

</div>
@endsection
