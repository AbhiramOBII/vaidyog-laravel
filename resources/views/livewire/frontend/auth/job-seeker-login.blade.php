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
                <h1 class="text-white font-bold text-3xl xl:text-4xl leading-tight mb-4">Find Your Next Healthcare Career</h1>
                <p class="text-white/80 text-base leading-relaxed mb-6">
                    Access thousands of healthcare job opportunities across India. Build your professional profile and get discovered by top hospitals and clinics.
                </p>

                <div class="space-y-3 mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Access 10,000+ healthcare jobs across India</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Get discovered by verified hospitals & clinics</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-white/90 text-sm">Track applications and build your medical career</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-[#4ab098] ring-2 ring-[#464d79] flex items-center justify-center text-white text-xs font-bold">D</div>
                        <div class="w-8 h-8 rounded-full bg-purple-500 ring-2 ring-[#464d79] flex items-center justify-center text-white text-xs font-bold">N</div>
                        <div class="w-8 h-8 rounded-full bg-amber-500 ring-2 ring-[#464d79] flex items-center justify-center text-white text-xs font-bold">P</div>
                    </div>
                    <p class="text-white/70 text-xs">Join 50,000+ healthcare professionals on Vaidyog</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL — Login Form --}}
    <div class="lg:col-span-5 flex items-center justify-center px-6 py-12 lg:px-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-10 mx-auto">
            </div>

            <h2 class="text-2xl font-bold text-neutral-900 mb-1">Welcome back</h2>
            <p class="text-sm text-neutral-500 mb-8">Sign in to your job seeker account</p>

            {{-- Method Toggle --}}
            <div class="flex bg-neutral-100 rounded-lg p-1 mb-6">
                <button wire:click="$set('loginMethod', 'email')" class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all {{ $loginMethod === 'email' ? 'bg-white text-neutral-900 shadow-sm' : 'text-neutral-500 hover:text-neutral-700' }}">
                    Email & Password
                </button>
                <button wire:click="$set('loginMethod', 'phone')" class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all {{ $loginMethod === 'phone' ? 'bg-white text-neutral-900 shadow-sm' : 'text-neutral-500 hover:text-neutral-700' }}">
                    Phone OTP
                </button>
            </div>

            {{-- Error --}}
            @if ($error)
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">{{ $error }}</div>
            @endif

            {{-- Email Login --}}
            @if ($loginMethod === 'email')
                <form wire:submit="loginWithEmail" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Email address</label>
                        <input wire:model="email" type="email" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="you@example.com" autofocus>
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Password</label>
                        <input wire:model="password" type="password" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="••••••••">
                        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 text-sm font-semibold text-white rounded-lg transition-all" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="loginWithEmail">Sign In</span>
                        <span wire:loading wire:target="loginWithEmail">Signing in...</span>
                    </button>
                </form>
            @else
                {{-- Phone OTP Login --}}
                @if (!$otpSent)
                    <form wire:submit="sendPhoneOtp" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Phone number</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-sm">+91</span>
                                <input wire:model="phone" type="tel" maxlength="10" class="flex-1 px-4 py-2.5 rounded-r-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm transition" placeholder="9876543210" autofocus>
                            </div>
                            @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 text-sm font-semibold text-white rounded-lg transition-all" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="sendPhoneOtp">Send OTP</span>
                            <span wire:loading wire:target="sendPhoneOtp">Sending...</span>
                        </button>
                    </form>
                @else
                    <form wire:submit="verifyPhoneOtp" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Enter OTP sent to +91 {{ $phone }}</label>
                            <input wire:model="otp" type="text" maxlength="6" class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] text-sm text-center tracking-[0.5em] font-mono transition" placeholder="000000" autofocus>
                            @error('otp') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 text-sm font-semibold text-white rounded-lg transition-all" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="verifyPhoneOtp">Verify & Sign In</span>
                            <span wire:loading wire:target="verifyPhoneOtp">Verifying...</span>
                        </button>
                        <div class="text-center" x-data="{ cooldown: @entangle('resendCooldown') }" x-init="let t = setInterval(() => { if(cooldown > 0) cooldown--; else clearInterval(t) }, 1000)">
                            <button wire:click="resendOtp" type="button" class="text-sm text-[#464d79] hover:underline disabled:text-neutral-400 disabled:no-underline" :disabled="cooldown > 0">
                                <span x-show="cooldown > 0">Resend in <span x-text="cooldown"></span>s</span>
                                <span x-show="cooldown === 0">Resend OTP</span>
                            </button>
                        </div>
                    </form>
                @endif
            @endif

            <div class="mt-8 text-center">
                <p class="text-sm text-neutral-500">Don't have an account? <a href="{{ route('jobseeker.register') }}" class="text-[#464d79] font-semibold hover:underline" wire:navigate>Register</a></p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('recruiter.login') }}" class="text-xs text-neutral-400 hover:text-neutral-600 transition-colors">Are you a recruiter? Login here →</a>
            </div>
        </div>
    </div>
</div>
