<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
    {{-- LEFT PANEL (7 cols) — Brand with background image --}}
    <div class="hidden lg:flex lg:flex-col lg:col-span-7 relative overflow-hidden">
        {{-- Background image --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1920&q=80')"></div>
        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-[#464d79]/85"></div>
        {{-- Gradient accents --}}
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 20% 90%, rgba(0,0,0,0.3) 0%, transparent 60%), radial-gradient(ellipse at 80% 10%, rgba(255,255,255,0.08) 0%, transparent 50%)"></div>

        <div class="relative z-10 flex flex-col h-full p-10 xl:p-14">
            {{-- Logo --}}
            <div class="mb-auto">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-12 brightness-0 invert">
            </div>

            {{-- Pitch --}}
            <div class="max-w-lg">
                <h1 class="text-white font-bold text-3xl xl:text-4xl leading-tight mb-4">India's Leading Healthcare Recruitment Platform</h1>
                <p class="text-white/80 text-base leading-relaxed mb-6">
                    Connect with 50,000+ verified healthcare professionals. Post jobs, screen candidates, and hire faster with AI-powered matching — all from one dashboard.
                </p>

                {{-- Feature points --}}
                <div class="space-y-3 mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Post unlimited jobs across all specialities</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">AI-powered candidate matching & recommendations</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Verified profiles with credential checks</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white/20">
                    <div>
                        <div class="text-white text-2xl xl:text-3xl font-bold">50K+</div>
                        <div class="text-white/60 text-xs mt-1 uppercase tracking-wide">Professionals</div>
                    </div>
                    <div>
                        <div class="text-white text-2xl xl:text-3xl font-bold">2,500+</div>
                        <div class="text-white/60 text-xs mt-1 uppercase tracking-wide">Institutions</div>
                    </div>
                    <div>
                        <div class="text-white text-2xl xl:text-3xl font-bold">12K+</div>
                        <div class="text-white/60 text-xs mt-1 uppercase tracking-wide">Active Jobs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL (5 cols) — Registration Form --}}
    <div class="lg:col-span-5 bg-white flex items-center justify-center p-6 sm:p-8 lg:p-10 overflow-y-auto min-h-screen">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="flex lg:hidden items-center gap-2 mb-8">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-10">
            </div>

            {{-- Form header --}}
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight text-neutral-900 mb-1">Create your account</h2>
                <p class="text-sm text-neutral-500">Register to start hiring healthcare professionals.</p>
            </div>

            {{-- Error --}}
            @if ($error)
                <div class="flex items-start gap-3 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ $error }}</span>
                </div>
            @endif

            {{-- Registration form --}}
            <form wire:submit="register" novalidate class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution / Name</label>
                    <input wire:model="name" type="text" placeholder="City Hospital" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('name') border-red-400 @enderror"/>
                    @error('name') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Email address</label>
                    <input wire:model="email" type="email" placeholder="admin@hospital.com" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('email') border-red-400 @enderror"/>
                    @error('email') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Phone number</label>
                    <div class="flex items-center gap-2">
                        <span class="h-11 px-3 flex items-center bg-neutral-100 border border-neutral-200 rounded-lg text-sm text-neutral-600 font-medium">+91</span>
                        <input wire:model="phone" type="tel" maxlength="10" placeholder="9876543210" class="flex-1 h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('phone') border-red-400 @enderror"/>
                    </div>
                    @error('phone') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution type</label>
                    <select wire:model="med_type" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('med_type') border-red-400 @enderror">
                        <option value="">Select type...</option>
                        @foreach($medTypes as $mt)<option value="{{ $mt->value }}">{{ $mt->label() }}</option>@endforeach
                    </select>
                    @error('med_type') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input wire:model="password" :type="show ? 'text' : 'password'" placeholder="Min 8 characters" class="w-full h-11 px-4 pr-12 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('password') border-red-400 @enderror"/>
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600 transition-colors">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" style="display:none"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Confirm password</label>
                    <input wire:model="password_confirmation" type="password" placeholder="Re-enter password" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full h-12 bg-[#464d79] hover:bg-[#3a4169] active:bg-[#2f3357] text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 transition-colors shadow-sm hover:shadow-md disabled:opacity-70 disabled:cursor-not-allowed mt-2">
                    <span wire:loading wire:target="register" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span wire:loading.remove wire:target="register" class="flex items-center gap-2">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L11.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 11-1.04-1.08l3.158-2.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </span>
                </button>
            </form>

            {{-- Google --}}
            <div class="mt-5 pt-5 border-t border-neutral-100">
                <a href="{{ route('recruiter.google.redirect') }}" class="w-full h-11 inline-flex items-center justify-center gap-3 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Sign up with Google
                </a>
            </div>

            {{-- Login Link --}}
            <p class="mt-5 text-center text-sm text-neutral-500">
                Already have an account? <a href="{{ route('recruiter.login') }}" class="font-medium text-[#464d79] hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
