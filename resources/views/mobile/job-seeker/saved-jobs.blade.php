@extends('mobile.layout')
@section('title', 'Saved Jobs')

@section('app_header')
<div class="bg-white px-5 py-3.5 flex items-center justify-between border-b border-neutral-100 shrink-0">
    <div>
        <p class="text-lg font-bold text-neutral-900">Saved Jobs</p>
        <p class="text-xs text-neutral-400">7 jobs saved</p>
    </div>
    <a href="{{ route('mobile.jobseeker.jobs') }}" class="flex items-center gap-1.5 text-xs font-semibold text-[#464d79] border border-[#464d79]/30 px-3 py-2 rounded-xl">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Find More
    </a>
</div>
@endsection

@section('content')
<div class="px-4 pt-4 space-y-3 pb-2">

    {{-- Expiring soon alert --}}
    <div class="flex items-center gap-2.5 bg-amber-50 border border-amber-200 rounded-2xl p-3">
        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-amber-700"><span class="font-semibold">2 jobs</span> are expiring within 3 days — apply soon!</p>
    </div>

    {{-- Saved Job Cards --}}
    @foreach([
        ['Senior Cardiologist','Apollo Hospitals','Chennai, Tamil Nadu','₹3–5L/mo','Full Time','5–15 yrs','Expires in 2 days','text-red-500',true],
        ['Cardiac ICU Specialist','Narayana Health','Bengaluru, Karnataka','₹2.5–4L/mo','Full Time','3–10 yrs','Expires in 3 days','text-amber-500',true],
        ['Interventional Cardiologist','Fortis Healthcare','Mumbai, Maharashtra','₹4–7L/mo','Full Time','8–20 yrs','Expires in 12 days','text-neutral-400',false],
        ['Cardiology Consultant','AIIMS Delhi','Delhi','₹2–3.5L/mo','Permanent','5–12 yrs','Expires in 18 days','text-neutral-400',false],
        ['Pediatric Cardiologist','Manipal Hospitals','Pune, Maharashtra','₹2.5–4.5L/mo','Full Time','3–8 yrs','Expires in 21 days','text-neutral-400',false],
        ['Head of Cardiology','MAX Hospitals','New Delhi','₹6–10L/mo','Full Time','15+ yrs','Expires in 25 days','text-neutral-400',false],
        ['Echocardiography Specialist','Medanta','Gurugram, Haryana','₹2–3L/mo','Consulting','2–6 yrs','Expires in 30 days','text-neutral-400',false],
    ] as [$title,$hospital,$location,$salary,$type,$exp,$expiry,$expiryCls,$urgent])
    <div class="bg-white rounded-2xl border {{ $urgent ? 'border-amber-200' : 'border-neutral-100' }} overflow-hidden">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#464d79]/10 to-[#4ab098]/10 flex items-center justify-center shrink-0 border border-neutral-100">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-neutral-900 truncate">{{ $title }}</p>
                            <p class="text-xs text-[#464d79] font-medium mt-0.5">{{ $hospital }}</p>
                        </div>
                        <button class="p-1 shrink-0">
                            <svg class="w-5 h-5 text-[#464d79] fill-[#464d79]" fill="currentColor" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-[11px] text-neutral-400 flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            {{ $location }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-lg font-medium">{{ $type }}</span>
                            <span class="text-[11px] font-bold text-[#464d79]">{{ $salary }}</span>
                        </div>
                        <span class="text-[10px] {{ $expiryCls }} font-medium flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $expiry }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 pb-4 flex gap-2">
            <button class="flex-1 py-2 rounded-xl text-xs font-semibold text-white transition-all active:opacity-80" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                Apply Now
            </button>
            <button class="px-3 py-2 rounded-xl text-xs font-semibold text-neutral-600 bg-neutral-100 border border-neutral-200">
                Remove
            </button>
        </div>
    </div>
    @endforeach

</div>
@endsection
