<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
    {{-- LEFT PANEL — Brand --}}
    <div class="hidden md:flex md:flex-col bg-[#464d79] relative p-12">
        {{-- Gradient overlay --}}
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 20% 90%, rgba(0,0,0,0.25) 0%, transparent 60%), radial-gradient(ellipse at 85% 10%, rgba(255,255,255,0.07) 0%, transparent 50%)"></div>

        {{-- Logo --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="2" width="6" height="18" rx="2" fill="white"/>
                        <rect x="2" y="8" width="18" height="6" rx="2" fill="white"/>
                    </svg>
                </div>
                <div>
                    <span class="text-white font-bold text-xl tracking-tight">Vaidyog</span>
                    <p class="text-white/60 text-xs uppercase tracking-widest">Admin Portal</p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 mt-auto">
            <h1 class="text-white font-bold text-2xl leading-tight mb-3">India's Healthcare Career Platform</h1>
            <p class="text-white/75 text-sm leading-relaxed">Manage jobs, professionals, recruiters, and platform operations from one dashboard.</p>

            <div class="flex gap-8 mt-8 pt-8 border-t border-white/15">
                <div>
                    <div class="text-white text-xl font-bold">50K+</div>
                    <div class="text-white/60 text-xs mt-0.5">Professionals</div>
                </div>
                <div>
                    <div class="text-white text-xl font-bold">2,500+</div>
                    <div class="text-white/60 text-xs mt-0.5">Institutions</div>
                </div>
                <div>
                    <div class="text-white text-xl font-bold">12K+</div>
                    <div class="text-white/60 text-xs mt-0.5">Active Jobs</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL — Form --}}
    <div class="bg-white dark:bg-neutral-900 flex items-center justify-center p-8 md:p-12">
        <div class="w-full max-w-sm">
            {{-- Theme toggle --}}
            <div class="flex justify-end mb-8">
                <button
                    @click="dark = !dark"
                    class="w-9 h-9 rounded-full bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 flex items-center justify-center text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200 transition-colors"
                >
                    {{-- Sun icon (shown in dark mode) --}}
                    <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    {{-- Moon icon (shown in light mode) --}}
                    <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile logo --}}
            <div class="flex md:hidden items-center gap-2 mb-8">
                <div class="w-8 h-8 rounded-md bg-[#464d79] flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="2" width="6" height="18" rx="2" fill="white"/>
                        <rect x="2" y="8" width="18" height="6" rx="2" fill="white"/>
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight">Vaidyog</span>
            </div>

            {{-- Form header --}}
            <div class="mb-8">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-widest text-[#464d79] bg-[#464d79]/10 dark:bg-[#464d79]/20 px-3 py-1 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                    </svg>
                    Administrator Access
                </span>
                <h2 class="text-xl font-bold tracking-tight text-neutral-900 dark:text-white mb-1">Welcome back</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Sign in to access the admin dashboard.</p>
            </div>

            {{-- Error alert --}}
            @if($errorMessage)
                <div class="flex items-start gap-3 p-4 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 text-sm mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errorMessage }}</span>
                    <button wire:click="resetError" class="ml-auto shrink-0 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Login form --}}
            <form wire:submit="login" novalidate>
                {{-- Email field --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Email address</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </span>
                        <input
                            wire:model="email"
                            type="email"
                            id="email"
                            placeholder="admin@vaidyog.com"
                            class="w-full h-11 pl-10 pr-4 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 dark:focus:border-[#4ab098] dark:focus:ring-[#4ab098]/20 transition-all @error('email') border-red-400 bg-red-50 dark:bg-red-950/20 @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password field --}}
                <div class="mb-4" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <input
                            wire:model="password"
                            :type="show ? 'text' : 'password'"
                            id="password"
                            placeholder="••••••••"
                            class="w-full h-11 pl-10 pr-12 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 dark:focus:border-[#4ab098] dark:focus:ring-[#4ab098]/20 transition-all @error('password') border-red-400 bg-red-50 dark:bg-red-950/20 @enderror"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 rounded transition-colors"
                        >
                            {{-- Eye icon --}}
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            {{-- Eye-slash icon --}}
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between mt-1 mb-6">
                    <label class="flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400 cursor-pointer select-none">
                        <input wire:model="rememberMe" type="checkbox" class="w-4 h-4 rounded border-neutral-300 dark:border-neutral-600 accent-[#464d79] cursor-pointer"/>
                        Remember me
                    </label>
                    <a href="#" class="text-sm font-medium text-[#464d79] dark:text-[#4ab098] hover:underline">Forgot password?</a>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full h-12 bg-[#464d79] hover:bg-[#3a4169] active:bg-[#2f3357] text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 transition-colors shadow-sm hover:shadow-md disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <span wire:loading class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span wire:loading.remove class="flex items-center gap-2">
                        Sign in to Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L11.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 11-1.04-1.08l3.158-2.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                </button>
            </form>

            {{-- Footer --}}
            <div class="mt-8 pt-6 border-t border-neutral-100 dark:border-neutral-800 text-center">
                <p class="text-xs text-neutral-400 dark:text-neutral-600 leading-relaxed">Protected area. Authorized personnel only.</p>
            </div>
        </div>
    </div>
</div>
