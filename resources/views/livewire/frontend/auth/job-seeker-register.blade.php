<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
    {{-- LEFT PANEL --}}
    <div class="hidden lg:flex lg:flex-col lg:col-span-7 relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?auto=format&fit=crop&w=1920&q=80')"></div>
        <div class="absolute inset-0 bg-[#464d79]/85"></div>
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 20% 90%, rgba(0,0,0,0.3) 0%, transparent 60%), radial-gradient(ellipse at 80% 10%, rgba(255,255,255,0.08) 0%, transparent 50%)"></div>

        <div class="relative z-10 flex flex-col h-full p-10 xl:p-14">
            <div class="mb-auto">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-12 brightness-0 invert">
            </div>

            <div class="max-w-lg">
                <h1 class="text-white font-bold text-3xl xl:text-4xl leading-tight mb-4">Start Your Healthcare Career Journey</h1>
                <p class="text-white/80 text-base leading-relaxed mb-6">
                    Create your free account and build a professional profile that gets noticed by healthcare recruiters across India.
                </p>

                <div class="space-y-3 mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Free profile creation — no credit card required</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Apply to unlimited healthcare positions</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Get job alerts matching your speciality</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL — Register Form --}}
    <div class="lg:col-span-5 flex items-center justify-center px-6 py-12 lg:px-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-10 mx-auto">
            </div>

            <h2 class="text-2xl font-bold text-neutral-900 mb-1">Create your account</h2>
            <p class="text-sm text-neutral-500 mb-8">Join Vaidyog and find your perfect healthcare role</p>

            <form wire:submit="register" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Full name</label>
                    <input wire:model="name" type="text" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="Dr. Rahul Sharma" autofocus>
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Email address</label>
                    <input wire:model="email" type="email" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="you@example.com">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Phone number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-sm">+91</span>
                        <input wire:model="phone" type="tel" maxlength="10" class="flex-1 px-4 py-2.5 rounded-r-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="9876543210">
                    </div>
                    @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Password</label>
                    <input wire:model="password" type="password" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="Min. 8 characters">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Confirm password</label>
                    <input wire:model="password_confirmation" type="password" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="Re-enter password">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 text-sm font-semibold text-white rounded-lg transition-all" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="register">Create Account</span>
                    <span wire:loading wire:target="register">Creating account...</span>
                </button>

                <p class="text-xs text-neutral-400 text-center">By registering, you agree to our Terms of Service and Privacy Policy.</p>
            </form>

            {{-- Google Sign-up --}}
            <div class="mt-6 pt-6 border-t border-neutral-100">
                <a href="{{ route('jobseeker.google.redirect') }}" class="w-full h-11 inline-flex items-center justify-center gap-3 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Sign up with Google
                </a>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-neutral-500">Already have an account? <a href="{{ route('jobseeker.login') }}" class="text-[#464d79] font-semibold hover:underline" wire:navigate>Sign In</a></p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('recruiter.register') }}" class="text-xs text-neutral-400 hover:text-neutral-600 transition-colors">Are you a recruiter? Register here →</a>
            </div>
        </div>
    </div>
</div>
