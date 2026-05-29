<nav x-data="{ mobileMenu: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 60 })"
     :class="scrolled ? 'shadow-md' : 'shadow-sm'"
     class="sticky top-0 z-50 bg-white border-b border-gray-200 transition-shadow duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Left: Logo --}}
            <a href="/" class="shrink-0">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-14 w-auto"/>
            </a>

            {{-- Center: Navigation Menu --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors {{ request()->is('/') ? 'text-primary-500' : '' }}">Home</a>
                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors {{ request()->is('best-healthcare-jobs*') ? 'text-primary-500' : '' }}">Browse Jobs</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors {{ request()->is('about-vaidyog-healthcare-job-site') ? 'text-primary-500' : '' }}">About</a>
                <a href="{{ route('plans.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors {{ request()->is('plans*') ? 'text-primary-500' : '' }}">Plans</a>
                <a href="{{ route('recruiter.login') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors">For Recruiters</a>
            </div>

            {{-- Right: Action Buttons --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->user_type === 'MedicalInstitution')
                        <a href="/recruiter/dashboard" class="px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">My Dashboard</a>
                    @else
                        <a href="{{ route('jobseeker.dashboard') }}" class="px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">My Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('jobseeker.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('jobseeker.login') }}" class="px-5 py-2.5 text-sm font-medium text-primary-600 border border-primary-200 rounded-lg hover:bg-primary-50 transition-colors">Login</a>
                    <a href="{{ route('jobseeker.register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-primary-500 rounded-lg hover:bg-primary-600 transition-colors">Register</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile slide-out menu --}}
    <template x-teleport="body">
        <div x-show="mobileMenu" x-cloak class="fixed inset-0 z-[100] md:hidden">
            {{-- Backdrop --}}
            <div x-show="mobileMenu"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenu = false"
                 class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            {{-- Panel --}}
            <div x-show="mobileMenu"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="absolute top-0 right-0 w-[280px] h-full bg-white shadow-2xl flex flex-col">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <a href="/">
                        <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-10 w-auto"/>
                    </a>
                    <button @click="mobileMenu = false" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Navigation links --}}
                <nav class="flex-1 overflow-y-auto px-5 py-5 space-y-1">
                    <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('/') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('best-healthcare-jobs*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Browse Jobs
                    </a>
                    <a href="{{ route('about') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('about-vaidyog-healthcare-job-site') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        About
                    </a>
                    <a href="{{ route('plans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('plans*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Plans
                    </a>
                    <a href="{{ route('recruiter.login') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        For Recruiters
                    </a>
                </nav>

                {{-- Footer actions --}}
                <div class="border-t border-gray-100 px-5 py-5 space-y-3">
                    @auth
                        @if(auth()->user()->user_type === 'MedicalInstitution')
                            <a href="/recruiter/dashboard" class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                My Dashboard
                            </a>
                        @else
                            <a href="{{ route('jobseeker.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                My Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('jobseeker.logout') }}">
                            @csrf
                            <button class="w-full py-2.5 text-sm font-medium text-red-500 border border-red-100 rounded-lg hover:bg-red-50 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('jobseeker.login') }}" class="flex items-center justify-center w-full py-2.5 text-sm font-semibold text-[#464d79] border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Login</a>
                        <a href="{{ route('jobseeker.register') }}" class="flex items-center justify-center w-full py-2.5 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">Register Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </template>
</nav>
