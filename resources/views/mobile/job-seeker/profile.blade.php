@extends('mobile.layout')
@section('title', 'My Profile')

@section('app_header')
<div class="bg-white px-5 py-3.5 flex items-center justify-between border-b border-neutral-100 shrink-0">
    <p class="text-lg font-bold text-neutral-900">My Profile</p>
    <div class="flex items-center gap-2">
        <a href="{{ url('profile/dr-rahul-sharma') }}" target="_blank"
           class="text-[11px] font-semibold text-[#4ab098] border border-[#4ab098]/30 px-2.5 py-1.5 rounded-xl flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Public
        </a>
        <button class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#464d79]/10">
            <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="pb-2">

    {{-- Profile Hero --}}
    <div class="relative">
        <div class="h-28" style="background:linear-gradient(135deg,#464d79 0%,#5a6399 50%,#4ab098 100%)">
            <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle,white 1px,transparent 1px);background-size:20px 20px"></div>
        </div>
        <div class="px-5 pb-4 bg-white">
            <div class="flex items-end justify-between -mt-10 mb-3">
                <img src="https://ui-avatars.com/api/?name=Rahul+Sharma&background=4ab098&color=fff&size=128"
                     class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg">
                <span class="mb-1 flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full border border-green-200">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    Open to Work
                </span>
            </div>
            <h1 class="text-xl font-bold text-neutral-900">Dr. Rahul Sharma</h1>
            <p class="text-sm text-[#464d79] font-semibold mt-0.5">Senior Cardiologist</p>
            <p class="text-xs text-neutral-500 mt-0.5">Interventional Cardiology · Apollo Hospitals</p>
            <div class="flex items-center gap-1 mt-2 text-xs text-neutral-400">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                Bengaluru, Karnataka · 8 years experience
            </div>
        </div>
    </div>

    <div class="px-4 space-y-3 pt-2">

        {{-- Profile Completeness --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-semibold text-neutral-800">Profile Completeness</p>
                <span class="text-sm font-bold text-[#4ab098]">68%</span>
            </div>
            <div class="w-full bg-neutral-100 rounded-full h-2 mb-2.5">
                <div class="h-2 rounded-full bg-gradient-to-r from-[#464d79] to-[#4ab098]" style="width:68%"></div>
            </div>
            <div class="flex flex-wrap gap-1.5">
                @foreach(['Add Photo','Upload CV','Add Skills','Add Projects'] as $item)
                <span class="text-[11px] px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ $item }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- About --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-2.5">
                <p class="text-sm font-bold text-neutral-900">About</p>
                <button class="text-xs text-[#464d79] font-semibold">Edit</button>
            </div>
            <p class="text-xs text-neutral-600 leading-relaxed">
                Board-certified cardiologist with 8+ years of experience in interventional cardiology. Specialised in complex coronary interventions, TAVR procedures, and cardiac rehabilitation. Published researcher with 12 peer-reviewed papers.
            </p>
        </div>

        {{-- Skills --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-neutral-900">Key Skills</p>
                <button class="text-xs text-[#464d79] font-semibold">Edit</button>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(['Interventional Cardiology','Echocardiography','TAVR','Cardiac Catheterisation','Heart Failure Mgmt','Pacemaker Implant','Critical Care','Patient Counselling'] as $skill)
                <span class="text-[11px] px-3 py-1.5 rounded-full bg-[#464d79]/8 text-[#464d79] border border-[#464d79]/15 font-medium">{{ $skill }}</span>
                @endforeach
            </div>
        </div>

        {{-- Experience --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-neutral-900">Experience</p>
                <button class="text-xs text-[#464d79] font-semibold">+ Add</button>
            </div>
            <div class="space-y-4">
                @foreach([
                    ['Senior Cardiologist','Apollo Hospitals','Jan 2020 – Present','Chennai, Tamil Nadu',true],
                    ['Cardiology Consultant','Fortis Healthcare','Jun 2017 – Dec 2019','Mumbai, Maharashtra',false],
                ] as [$role,$company,$duration,$loc,$current])
                <div class="relative pl-5 border-l-2 {{ $current ? 'border-[#4ab098]' : 'border-neutral-200' }}">
                    <div class="absolute left-0 top-1 -translate-x-1/2 w-3 h-3 rounded-full {{ $current ? 'bg-[#4ab098]' : 'bg-neutral-300' }} border-2 border-white"></div>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">{{ $role }}</p>
                            <p class="text-xs text-[#464d79] font-medium mt-0.5">{{ $company }}</p>
                            <p class="text-[11px] text-neutral-400 mt-0.5">{{ $duration }} · {{ $loc }}</p>
                        </div>
                        @if($current)
                        <span class="text-[10px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-semibold shrink-0">Current</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Education --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-neutral-900">Education</p>
                <button class="text-xs text-[#464d79] font-semibold">+ Add</button>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['DM Cardiology','AIIMS Delhi','2014 – 2017','Cardiology'],
                    ['MBBS','Maulana Azad Medical College','2006 – 2012','Medicine'],
                ] as [$degree,$inst,$years,$field])
                <div class="relative pl-5 border-l-2 border-neutral-200">
                    <div class="absolute left-0 top-1 -translate-x-1/2 w-3 h-3 rounded-full bg-neutral-300 border-2 border-white"></div>
                    <p class="text-sm font-semibold text-neutral-900">{{ $degree }}</p>
                    <p class="text-xs text-[#464d79] font-medium mt-0.5">{{ $inst }}</p>
                    <p class="text-[11px] text-neutral-400 mt-0.5">{{ $years }} · {{ $field }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Certifications --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-neutral-900">Certifications</p>
                <button class="text-xs text-[#464d79] font-semibold">+ Add</button>
            </div>
            <div class="space-y-2.5">
                @foreach([
                    ['FACC — Fellow, American College of Cardiology','ACC, USA','2019'],
                    ['FSCAI — Interventional Cardiology Fellowship','SCAI, USA','2018'],
                ] as [$cert,$org,$year])
                <div class="flex items-start gap-3 p-3 bg-neutral-50 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-[#464d79]/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-neutral-900 leading-snug">{{ $cert }}</p>
                        <p class="text-[10px] text-neutral-400 mt-0.5">{{ $org }} · {{ $year }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Languages --}}
        <div class="bg-white rounded-2xl border border-neutral-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-neutral-900">Languages</p>
                <button class="text-xs text-[#464d79] font-semibold">Edit</button>
            </div>
            <div class="space-y-2">
                @foreach([['English','Native'],['Hindi','Fluent'],['Tamil','Conversational']] as [$lang,$level])
                <div class="flex items-center justify-between">
                    <p class="text-sm text-neutral-700 font-medium">{{ $lang }}</p>
                    <span class="text-[11px] px-2.5 py-1 rounded-full font-medium {{ $level === 'Native' ? 'bg-green-100 text-green-700' : ($level === 'Fluent' ? 'bg-blue-100 text-blue-700' : 'bg-neutral-100 text-neutral-600') }}">{{ $level }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
