<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">

    {{-- Page Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#464d79]/10 to-[#4ab098]/10 text-sm font-medium text-[#464d79] mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            AI-Powered
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">Resume Builder</h1>
        <p class="text-neutral-500 mt-2">Create a professional, ATS-friendly resume in seconds</p>
    </div>

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center mb-10">
        <div class="flex items-center gap-0">
            @foreach([['Input', '1'], ['Processing', '2'], ['Your Resume', '3']] as [$label, $num])
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                            {{ $step >= (int)$num ? 'bg-[#464d79] text-white shadow-lg shadow-[#464d79]/20' : 'bg-neutral-200 text-neutral-500' }}">
                            @if($step > (int)$num)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <span class="text-xs mt-1.5 font-medium {{ $step >= (int)$num ? 'text-[#464d79]' : 'text-neutral-400' }}">{{ $label }}</span>
                    </div>
                    @if($num !== '3')
                        <div class="w-16 sm:w-24 h-0.5 mx-2 mb-5 rounded {{ $step > (int)$num ? 'bg-[#464d79]' : 'bg-neutral-200' }} transition-all duration-300"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Error Message --}}
    @if($error)
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ $error }}</span>
        </div>
    @endif

    {{-- ======== STEP 1: Input ======== --}}
    @if($step === 1)
    <div class="space-y-6" x-data="{ method: @entangle('inputMethod') }">

        {{-- Input Method Toggle --}}
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-base font-semibold text-neutral-900 mb-4">How would you like to provide your information?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="inputMethod" value="file" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 transition-all peer-checked:border-[#464d79] peer-checked:bg-[#464d79]/5 border-neutral-200 hover:border-neutral-300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-900 text-sm">Upload Existing Resume</p>
                                <p class="text-xs text-neutral-500">PDF, DOC, DOCX, or TXT</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="inputMethod" value="manual" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 transition-all peer-checked:border-[#464d79] peer-checked:bg-[#464d79]/5 border-neutral-200 hover:border-neutral-300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#4ab098]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4ab098]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-900 text-sm">Type / Paste Content</p>
                                <p class="text-xs text-neutral-500">Enter your details manually</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- File Upload Area --}}
        <div x-show="method === 'file'" x-transition class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-base font-semibold text-neutral-900 mb-4">Upload Your Resume</h2>
            <div class="relative">
                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-neutral-300 rounded-xl cursor-pointer hover:border-[#464d79]/50 hover:bg-[#464d79]/5 transition-all">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        @if($resumeFile)
                            <svg class="w-10 h-10 text-[#4ab098] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-medium text-[#4ab098]">{{ $resumeFile->getClientOriginalName() }}</p>
                            <p class="text-xs text-neutral-500 mt-1">Click to replace</p>
                        @else
                            <svg class="w-10 h-10 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-sm text-neutral-600"><span class="font-semibold text-[#464d79]">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-neutral-500 mt-1">PDF, DOC, DOCX, TXT (Max 5MB)</p>
                        @endif
                    </div>
                    <input type="file" wire:model="resumeFile" accept=".pdf,.doc,.docx,.txt" class="hidden"/>
                </label>
            </div>
            @error('resumeFile') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Manual Input Area --}}
        <div x-show="method === 'manual'" x-transition class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-base font-semibold text-neutral-900 mb-4">Enter Your Details</h2>
            <textarea wire:model="manualContent" rows="10" placeholder="Paste your existing resume content here, or type in your details:&#10;&#10;• Full name and contact info&#10;• Professional summary&#10;• Work experience (job titles, companies, dates, responsibilities)&#10;• Education (degrees, institutions, years)&#10;• Skills and certifications&#10;• Languages spoken" class="w-full px-4 py-3 border border-neutral-300 rounded-xl text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors resize-none placeholder:text-neutral-400"></textarea>
            @error('manualContent') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Options --}}
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-base font-semibold text-neutral-900 mb-4">Options</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Tone --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Resume Tone</label>
                    <select wire:model="tone" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="professional">Professional</option>
                        <option value="academic">Academic / Research</option>
                        <option value="creative">Creative & Engaging</option>
                        <option value="concise">Concise & Impactful</option>
                    </select>
                </div>
                {{-- Include Image --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Profile Photo</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors">
                        <div class="relative">
                            <input type="checkbox" wire:model="includeImage" class="sr-only peer">
                            <div class="w-10 h-5 bg-neutral-300 rounded-full peer-checked:bg-[#4ab098] transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <span class="text-sm text-neutral-700">Include profile photo in PDF</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button wire:click="startProcessing" wire:loading.attr="disabled" class="px-8 py-3 bg-[#464d79] text-white font-semibold rounded-xl hover:bg-[#3a4066] shadow-lg shadow-[#464d79]/20 transition-all flex items-center gap-2 disabled:opacity-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Generate Resume
            </button>
        </div>
    </div>
    @endif

    {{-- ======== STEP 2: Processing ======== --}}
    @if($step === 2)
    <div class="bg-white rounded-xl border border-neutral-200 p-10 text-center" wire:init="processResume">
        <div class="max-w-sm mx-auto">
            {{-- Animated graphic --}}
            <div class="relative w-32 h-32 mx-auto mb-8">
                {{-- Outer ring --}}
                <div class="absolute inset-0 rounded-full border-4 border-[#464d79]/10"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#464d79] animate-spin"></div>
                {{-- Middle ring --}}
                <div class="absolute inset-3 rounded-full border-4 border-[#4ab098]/10"></div>
                <div class="absolute inset-3 rounded-full border-4 border-transparent border-t-[#4ab098] animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                {{-- Inner icon --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#464d79] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>

            <h2 class="text-xl font-bold text-neutral-900 mb-2">AI is building your resume</h2>
            <p class="text-neutral-500 text-sm mb-4">{{ $processingStatus ?: 'Analyzing your content...' }}</p>

            {{-- Progress dots --}}
            <div class="flex items-center justify-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-[#464d79] animate-bounce" style="animation-delay: 0ms;"></div>
                <div class="w-2 h-2 rounded-full bg-[#464d79] animate-bounce" style="animation-delay: 150ms;"></div>
                <div class="w-2 h-2 rounded-full bg-[#464d79] animate-bounce" style="animation-delay: 300ms;"></div>
            </div>

            <p class="text-xs text-neutral-400 mt-6">This usually takes 10–20 seconds</p>
        </div>
    </div>
    @endif

    {{-- ======== STEP 3: Resume Display ======== --}}
    @if($step === 3)
    <div class="space-y-6">

        {{-- Action Bar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white rounded-xl border border-neutral-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-neutral-900 text-sm">Your resume is ready!</p>
                    <p class="text-xs text-neutral-500">Review below and download as PDF</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="$set('showUpdatePrompt', true)" class="px-4 py-2 text-sm font-medium border border-[#4ab098] text-[#4ab098] rounded-lg hover:bg-[#4ab098]/5 transition-colors">
                    Update Profile
                </button>
                <button wire:click="downloadPdf" class="px-4 py-2 text-sm font-medium bg-[#464d79] text-white rounded-lg hover:bg-[#3a4066] shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download PDF
                </button>
                @if($resumeSaved)
                    <span class="px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Resume Saved!
                    </span>
                @else
                    <button wire:click="saveResume" wire:loading.attr="disabled" wire:target="saveResume" class="px-4 py-2 text-sm font-medium bg-[#4ab098] text-white rounded-lg hover:bg-[#3d9680] shadow-md transition-all flex items-center gap-2 disabled:opacity-50">
                        <svg class="w-4 h-4" wire:loading.remove wire:target="saveResume" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <svg class="w-4 h-4 animate-spin" wire:loading wire:target="saveResume" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Save to Profile
                    </button>
                @endif
                <button wire:click="startOver" class="px-4 py-2 text-sm font-medium text-neutral-600 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors">
                    Start Over
                </button>
            </div>
        </div>

        {{-- Update Profile Prompt --}}
        @if($showUpdatePrompt)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5" x-data x-transition>
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-amber-800 text-sm">Update your Vaidyog profile?</h3>
                    <p class="text-xs text-amber-700 mt-1">We can update your profile with the improved skills, summary, and designation from this resume.</p>
                    <div class="flex items-center gap-2 mt-3">
                        <button wire:click="updateProfile" class="px-3 py-1.5 text-xs font-medium bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">Yes, update profile</button>
                        <button wire:click="$set('showUpdatePrompt', false)" class="px-3 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-900 transition-colors">No thanks</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Resume Preview Card --}}
        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#464d79] to-[#5a6399] p-8 text-white">
                <div class="flex items-start gap-5">
                    @if($includeImage && auth()->user()->jobSeekerProfile?->profile_photo_path)
                        <img src="{{ auth()->user()->jobSeekerProfile->getProfilePictureUrl() }}" class="w-20 h-20 rounded-full border-3 border-white/30 object-cover flex-shrink-0"/>
                    @endif
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">{{ $resumeData['name'] ?? '' }}</h1>
                        @if(!empty($resumeData['title']))
                            <p class="text-white/80 text-lg mt-1">{{ $resumeData['title'] }}</p>
                        @endif
                        @if(!empty($resumeData['contact']))
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-white/70">
                                @if(!empty($resumeData['contact']['email']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $resumeData['contact']['email'] }}
                                    </span>
                                @endif
                                @if(!empty($resumeData['contact']['phone']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $resumeData['contact']['phone'] }}
                                    </span>
                                @endif
                                @if(!empty($resumeData['contact']['location']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $resumeData['contact']['location'] }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">

                {{-- Summary --}}
                @if(!empty($resumeData['summary']))
                <div>
                    <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-2 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                        Professional Summary
                    </h2>
                    <p class="text-neutral-700 leading-relaxed">{{ $resumeData['summary'] }}</p>
                </div>
                @endif

                {{-- Experience --}}
                @if(!empty($resumeData['experience']))
                <div>
                    <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                        Professional Experience
                    </h2>
                    <div class="space-y-5">
                        @foreach($resumeData['experience'] as $exp)
                        <div class="pl-4 border-l-2 border-neutral-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <h3 class="font-semibold text-neutral-900">{{ $exp['title'] ?? '' }}</h3>
                                <span class="text-xs text-neutral-500 whitespace-nowrap">{{ $exp['period'] ?? '' }}</span>
                            </div>
                            @if(!empty($exp['company']))
                                <p class="text-sm text-[#464d79] font-medium">{{ $exp['company'] }}</p>
                            @endif
                            @if(!empty($exp['highlights']))
                                <ul class="mt-2 space-y-1">
                                    @foreach($exp['highlights'] as $highlight)
                                        <li class="text-sm text-neutral-600 flex items-start gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4ab098] flex-shrink-0 mt-1.5"></span>
                                            {{ $highlight }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Education --}}
                @if(!empty($resumeData['education']))
                <div>
                    <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                        Education
                    </h2>
                    <div class="space-y-3">
                        @foreach($resumeData['education'] as $edu)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                            <div>
                                <h3 class="font-semibold text-neutral-900 text-sm">{{ $edu['degree'] ?? '' }}</h3>
                                @if(!empty($edu['institution']))
                                    <p class="text-sm text-neutral-600">{{ $edu['institution'] }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-neutral-500">{{ $edu['year'] ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    {{-- Skills --}}
                    @if(!empty($resumeData['skills']))
                    <div>
                        <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-3 flex items-center gap-2">
                            <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                            Skills
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($resumeData['skills'] as $skill)
                                <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-neutral-100 text-neutral-700">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Certifications --}}
                    @if(!empty($resumeData['certifications']))
                    <div>
                        <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-3 flex items-center gap-2">
                            <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                            Certifications
                        </h2>
                        <ul class="space-y-1.5">
                            @foreach($resumeData['certifications'] as $cert)
                                <li class="text-sm text-neutral-700 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-[#4ab098] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    {{ $cert }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                {{-- Languages --}}
                @if(!empty($resumeData['languages']))
                <div>
                    <h2 class="text-sm font-bold text-[#464d79] uppercase tracking-wider mb-3 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#464d79] rounded"></div>
                        Languages
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($resumeData['languages'] as $lang)
                            <span class="px-3 py-1.5 text-xs font-medium rounded-full border border-neutral-200 text-neutral-700">{{ $lang }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
