@extends('mobile.layout')
@section('title', 'Home')

@section('app_header')
<div class="bg-white px-5 py-3 flex items-center justify-between border-b border-neutral-100 shrink-0">
    <div class="flex items-center gap-3">
        <img src="https://ui-avatars.com/api/?name=Rahul+Sharma&background=4ab098&color=fff&size=64"
             class="w-10 h-10 rounded-full object-cover border-2 border-[#4ab098]/30">
        <div>
            <p class="text-[11px] text-neutral-400">Good Morning 👋</p>
            <p class="text-sm font-bold text-neutral-900">Dr. Rahul Sharma</p>
        </div>
    </div>
    <button class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-neutral-100 active:bg-neutral-200">
        <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
    </button>
</div>
@endsection

@section('content')
<div class="px-4 pt-4 pb-2 space-y-4">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="rounded-2xl p-4 text-white relative overflow-hidden" style="background:linear-gradient(135deg,#464d79 0%,#5b63a0 100%)">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <p class="text-2xl font-bold">68%</p>
                <p class="text-xs text-white/70 mt-0.5">Profile Complete</p>
                <div class="mt-2.5 w-full bg-white/20 rounded-full h-1.5">
                    <div class="h-1.5 bg-white rounded-full" style="width:68%"></div>
                </div>
            </div>
        </div>
        <div class="rounded-2xl p-4 text-white relative overflow-hidden" style="background:linear-gradient(135deg,#4ab098 0%,#3d9680 100%)">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <p class="text-2xl font-bold">12</p>
                <p class="text-xs text-white/70 mt-0.5">Applications</p>
                <p class="text-[11px] text-white/60 mt-1.5">3 under review</p>
            </div>
        </div>
        <div class="rounded-2xl p-4 text-white relative overflow-hidden" style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <p class="text-2xl font-bold">7</p>
                <p class="text-xs text-white/70 mt-0.5">Saved Jobs</p>
                <p class="text-[11px] text-white/60 mt-1.5">2 expiring soon</p>
            </div>
        </div>
        <div class="rounded-2xl p-4 text-white relative overflow-hidden" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <p class="text-lg font-bold mt-0.5">Active</p>
                <p class="text-xs text-white/70 mt-0.5">Open to Work</p>
                <div class="mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-white/70">Visible to recruiters</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Resume Alert --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl p-3.5">
        <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-xs font-semibold text-amber-800">Resume not uploaded</p>
            <p class="text-[11px] text-amber-600 mt-0.5">Add your CV to get discovered by recruiters.</p>
        </div>
        <button class="text-[11px] font-semibold text-amber-700 bg-amber-100 px-2.5 py-1.5 rounded-lg shrink-0">Upload</button>
    </div>

    {{-- AI Resume Banner --}}
    <div class="bg-white rounded-2xl p-4 border border-neutral-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-bold text-neutral-900">AI Resume Builder</p>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white rounded-full" style="background:linear-gradient(90deg,#464d79,#4ab098)">NEW</span>
                </div>
                <p class="text-[11px] text-neutral-500 mt-0.5">Build an ATS-friendly resume with AI</p>
            </div>
            <button class="text-xs font-semibold text-[#464d79] border border-[#464d79]/30 px-3 py-1.5 rounded-xl shrink-0">Try →</button>
        </div>
    </div>

    {{-- Recent Jobs --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <p class="font-bold text-neutral-900">Recent Jobs</p>
            <a href="{{ route('mobile.jobseeker.jobs') }}" class="text-xs font-semibold text-[#464d79]">View All →</a>
        </div>
        <div class="space-y-3">

            @foreach([
                ['Senior Cardiologist','Apollo Hospitals','Chennai','₹3–5L/mo','Full Time','Featured','bg-green-100 text-green-700','bg-blue-50 border-blue-100 text-blue-600','M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['Cardiac ICU Specialist','Narayana Health','Bengaluru','₹2.5–4L/mo','Full Time','Remote','bg-blue-100 text-blue-700','bg-teal-50 border-teal-100 text-teal-600','M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['Cardiology Consultant','AIIMS Delhi','Delhi','₹2–3.5L/mo','Permanent','Govt','bg-purple-100 text-purple-700','bg-purple-50 border-purple-100 text-purple-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ] as [$title,$hospital,$city,$salary,$type,$badge,$badgeCls,$iconCls,$iconPath])
            <a href="{{ route('mobile.jobseeker.jobs') }}" class="block bg-white rounded-2xl p-4 border border-neutral-100 active:bg-neutral-50">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border {{ $iconCls }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ $title }}</p>
                                <p class="text-xs text-neutral-500">{{ $hospital }}</p>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold shrink-0 {{ $badgeCls }}">{{ $badge }}</span>
                        </div>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[11px] text-neutral-400 flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $city }}
                            </span>
                            <span class="text-[11px] text-neutral-400">{{ $type }}</span>
                            <span class="text-[11px] font-semibold text-[#464d79]">{{ $salary }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach

        </div>
    </div>

</div>
@endsection
