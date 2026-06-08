@extends('mobile.layout')
@section('title', 'Edit Profile')

@section('app_header')
<div class="bg-white border-b border-neutral-100 shrink-0">
    {{-- Top bar --}}
    <div class="px-4 py-3 flex items-center gap-3">
        <a href="{{ route('mobile.jobseeker.profile') }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-neutral-100 shrink-0">
            <svg class="w-4 h-4 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex-1">
            <p class="text-base font-bold text-neutral-900">Edit Profile</p>
            <p class="text-[11px] text-neutral-400">Update your information</p>
        </div>
        <button class="px-3 py-1.5 text-xs font-semibold text-white rounded-xl" style="background:linear-gradient(135deg,#464d79,#4ab098)">Save All</button>
    </div>
    {{-- Section tabs --}}
    <div class="flex gap-1 px-3 pb-3 overflow-x-auto hide-scroll">
        @foreach(['Photo','Personal','Professional','Languages','Resume'] as $i => $tab)
        <button class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $i === 0 ? 'bg-[#464d79] text-white shadow-md shadow-[#464d79]/20' : 'bg-neutral-100 text-neutral-500' }}">{{ $tab }}</button>
        @endforeach
    </div>
</div>
@endsection

@section('content')
<div class="px-4 pt-4 pb-2 space-y-4">

    {{-- ─── SECTION 1: Profile Photo ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-neutral-50 flex items-center gap-2">
            <div class="w-5 h-5 rounded-md bg-[#464d79]/10 flex items-center justify-center">
                <svg class="w-3 h-3 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-neutral-900">Profile Photo</p>
        </div>
        <div class="p-4">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Rahul+Sharma&background=4ab098&color=fff&size=128"
                         class="w-20 h-20 rounded-2xl object-cover border-2 border-[#4ab098]/20">
                    <button class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full bg-[#464d79] flex items-center justify-center shadow-lg border-2 border-white">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-neutral-800">Dr. Rahul Sharma</p>
                    <p class="text-xs text-neutral-400 mt-0.5">JPEG, PNG or WebP · Max 2MB</p>
                    <div class="flex items-center gap-2 mt-3">
                        <button class="flex-1 py-2 rounded-xl text-xs font-semibold text-white" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                            Change Photo
                        </button>
                        <button class="px-3 py-2 rounded-xl text-xs font-semibold text-red-500 bg-red-50 border border-red-100">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── SECTION 2: Personal Information ─────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-neutral-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-blue-50 flex items-center justify-center">
                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <p class="text-sm font-bold text-neutral-900">Personal Information</p>
            </div>
            <span class="text-[11px] font-semibold text-green-600 flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Saved
            </span>
        </div>
        <div class="p-4 space-y-3.5">

            {{-- Salutation + Name row --}}
            <div class="flex gap-2">
                <div class="w-24 shrink-0">
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Title</label>
                    <select class="w-full px-2.5 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Dr</option>
                        <option>Mr</option>
                        <option>Mrs</option>
                        <option>Ms</option>
                        <option>Prof</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">First Name <span class="text-red-400">*</span></label>
                    <input type="text" value="Rahul" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Last Name <span class="text-red-400">*</span></label>
                    <input type="text" value="Sharma" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
            </div>

            {{-- DOB + Gender --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Date of Birth <span class="text-red-400">*</span></label>
                    <input type="date" value="1988-03-15" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Gender</label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                        <option>Prefer not to say</option>
                    </select>
                </div>
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Phone <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-neutral-400 font-medium">+91</span>
                    <input type="tel" value="9876543210" class="w-full pl-11 pr-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
            </div>

            {{-- Email (disabled) --}}
            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Email</label>
                <div class="relative">
                    <input type="email" value="rahul.sharma@example.com" disabled class="w-full px-3 py-2.5 bg-neutral-100 border border-neutral-200 rounded-xl text-sm text-neutral-400 cursor-not-allowed">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                </div>
                <p class="text-[10px] text-neutral-400 mt-1">Email cannot be changed here</p>
            </div>

            {{-- Country / State / City --}}
            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Country <span class="text-red-400">*</span></label>
                <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                    <option>India</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">State <span class="text-red-400">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Karnataka</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">City <span class="text-red-400">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Bengaluru</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Pincode</label>
                    <input type="text" value="560001" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Nationality <span class="text-red-400">*</span></label>
                    <input type="text" value="Indian" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
            </div>

            <button class="w-full py-3 rounded-xl text-sm font-semibold text-white mt-1" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                Save Personal Info
            </button>
        </div>
    </div>

    {{-- ─── SECTION 3: Professional Identity ─────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-neutral-50 flex items-center gap-2">
            <div class="w-5 h-5 rounded-md bg-teal-50 flex items-center justify-center">
                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-neutral-900">Professional Identity</p>
        </div>
        <div class="p-4 space-y-3.5">

            {{-- Designation + Sub --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Designation <span class="text-red-400">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Doctor</option>
                        <option>Nurse</option>
                        <option>Pharmacist</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Sub-designation <span class="text-red-400">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Cardiologist</option>
                    </select>
                </div>
            </div>

            {{-- Experience + Employer --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Experience (Yrs)</label>
                    <input type="number" value="8" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Highest Qualification</label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>DM</option>
                        <option>MD</option>
                        <option>MBBS</option>
                        <option>MS</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Current Employer</label>
                <input type="text" value="Apollo Hospitals" placeholder="Hospital / Institution name" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
            </div>

            {{-- Category + Sub --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Job Category</label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Doctors</option>
                        <option>Nurses</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Specialty</label>
                    <select class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                        <option>Cardiology</option>
                    </select>
                </div>
            </div>

            {{-- Skills --}}
            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">Key Skills <span class="text-red-400">*</span></label>
                <div class="flex flex-wrap gap-1.5 mb-2.5">
                    @foreach(['Interventional Cardiology','Echocardiography','TAVR','Cardiac Catheterisation','Heart Failure Mgmt','Pacemaker Implant'] as $skill)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#4ab098]/10 text-[#3d9680] text-xs font-semibold rounded-full">
                        {{ $skill }}
                        <button class="text-[#4ab098]/60 hover:text-red-400 transition-colors leading-none">&times;</button>
                    </span>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <input type="text" placeholder="Type a skill and press Add" class="flex-1 px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10">
                    <button class="px-3 py-2.5 text-xs font-semibold text-[#464d79] border border-[#464d79]/30 bg-[#464d79]/5 rounded-xl shrink-0">+ Add</button>
                </div>
                <p class="text-[10px] text-neutral-400 mt-1.5">6/30 skills · Minimum 1 required</p>
            </div>

            {{-- About --}}
            <div>
                <label class="block text-[11px] font-semibold text-neutral-500 mb-1.5 uppercase tracking-wider">About / Summary</label>
                <textarea rows="4" maxlength="1000" class="w-full px-3 py-2.5 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/10 resize-none" placeholder="Write a short bio (min 50 characters)">Board-certified cardiologist with 8+ years of experience in interventional cardiology. Specialised in complex coronary interventions, TAVR procedures, and cardiac rehabilitation.</textarea>
                <p class="text-[10px] text-neutral-400 mt-1 text-right">182/1000</p>
            </div>

            {{-- Open to Work toggle --}}
            <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                <div>
                    <p class="text-sm font-semibold text-neutral-800">Open to Work</p>
                    <p class="text-[11px] text-neutral-400 mt-0.5">Make profile visible to recruiters</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-neutral-200 rounded-full peer peer-checked:bg-[#4ab098] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                </label>
            </div>

            <button class="w-full py-3 rounded-xl text-sm font-semibold text-white" style="background:linear-gradient(135deg,#464d79,#4ab098)">
                Save Professional Info
            </button>
        </div>
    </div>

    {{-- ─── SECTION 4: Languages ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-neutral-50 flex items-center gap-2">
            <div class="w-5 h-5 rounded-md bg-purple-50 flex items-center justify-center">
                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            </div>
            <p class="text-sm font-bold text-neutral-900">Languages Known</p>
        </div>
        <div class="p-4 space-y-3">

            {{-- Existing language entries --}}
            @foreach([['English','Native','bg-green-100 text-green-700'],['Hindi','Fluent','bg-blue-100 text-blue-700'],['Tamil','Conversational','bg-purple-100 text-purple-700']] as [$lang,$level,$cls])
            <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-neutral-800">{{ $lang }}</p>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $cls }}">{{ $level }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <select class="text-xs px-2 py-1.5 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:border-[#464d79]">
                        <option {{ $level === 'Native' ? 'selected' : '' }}>Native</option>
                        <option {{ $level === 'Fluent' ? 'selected' : '' }}>Fluent</option>
                        <option {{ $level === 'Conversational' ? 'selected' : '' }}>Conversational</option>
                        <option {{ $level === 'Basic' ? 'selected' : '' }}>Basic</option>
                    </select>
                    <button class="w-7 h-7 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach

            {{-- Add language --}}
            <div class="p-3 bg-[#464d79]/3 rounded-xl border border-[#464d79]/10 border-dashed">
                <p class="text-xs font-semibold text-neutral-600 mb-2">Add Language</p>
                <div class="flex gap-2">
                    <input type="text" placeholder="Language name" class="flex-1 px-3 py-2 bg-white border border-neutral-200 rounded-xl text-xs focus:outline-none focus:border-[#464d79]">
                    <select class="px-2 py-2 bg-white border border-neutral-200 rounded-xl text-xs focus:outline-none focus:border-[#464d79]">
                        <option>Native</option>
                        <option>Fluent</option>
                        <option>Conversational</option>
                        <option>Basic</option>
                    </select>
                    <button class="px-3 py-2 rounded-xl text-xs font-semibold text-white shrink-0" style="background:linear-gradient(135deg,#464d79,#4ab098)">Add</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── SECTION 5: Resume ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-neutral-50 flex items-center gap-2">
            <div class="w-5 h-5 rounded-md bg-red-50 flex items-center justify-center">
                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-neutral-900">Resume / CV</p>
        </div>
        <div class="p-4 space-y-3">

            {{-- Existing resume --}}
            <div class="flex items-center gap-3 p-3.5 bg-red-50 rounded-xl border border-red-100">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-neutral-800 truncate">Rahul_Sharma_CV.pdf</p>
                    <p class="text-[11px] text-neutral-500 mt-0.5">Uploaded 3 days ago · 1.2 MB</p>
                </div>
                <div class="flex flex-col gap-1.5">
                    <button class="text-[10px] font-semibold text-[#464d79] bg-white border border-[#464d79]/20 px-2 py-1 rounded-lg">View</button>
                    <button class="text-[10px] font-semibold text-red-500 bg-white border border-red-200 px-2 py-1 rounded-lg">Delete</button>
                </div>
            </div>

            {{-- Upload new --}}
            <div class="p-4 rounded-xl border-2 border-dashed border-neutral-200 bg-neutral-50 text-center">
                <div class="w-10 h-10 rounded-xl bg-[#464d79]/10 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <p class="text-sm font-semibold text-neutral-700">Upload New Resume</p>
                <p class="text-[11px] text-neutral-400 mt-0.5">PDF only · Max 5MB</p>
                <button class="mt-3 px-5 py-2 rounded-xl text-xs font-semibold text-[#464d79] border border-[#464d79]/30 bg-white">Browse File</button>
            </div>

        </div>
    </div>

    {{-- Bottom padding --}}
    <div class="h-2"></div>

</div>
@endsection
