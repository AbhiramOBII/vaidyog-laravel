<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $pageTitle ?? 'Vaidyog' }} — Healthcare Jobs India</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    @livewireStyles
</head>
<body class="antialiased bg-neutral-50 dark:bg-neutral-950 min-h-screen" x-data="{ mobileMenu: false }">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-30 bg-white/90 dark:bg-neutral-900/90 backdrop-blur border-b border-neutral-200 dark:border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2" wire:navigate>
                    <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-8 object-contain">
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden sm:flex items-center gap-6">
                    @auth
                    <a href="{{ route('jobseeker.dashboard') }}" wire:navigate class="text-sm font-medium {{ request()->routeIs('jobseeker.dashboard') ? 'text-[#464d79]' : 'text-neutral-600 hover:text-neutral-900' }} transition-colors">Dashboard</a>
                    @endauth
                    <a href="{{ route('jobs.index') }}" wire:navigate class="text-sm font-medium {{ request()->routeIs('jobs.*') ? 'text-[#464d79]' : 'text-neutral-600 hover:text-neutral-900' }} transition-colors">Jobs</a>
                    @auth
                    <a href="{{ route('jobseeker.applications.index') }}" wire:navigate class="text-sm font-medium {{ request()->routeIs('jobseeker.applications.*') ? 'text-[#464d79]' : 'text-neutral-600 hover:text-neutral-900' }} transition-colors">My Applications</a>
                    <a href="{{ route('jobseeker.saved-jobs.index') }}" wire:navigate class="text-sm font-medium {{ request()->routeIs('jobseeker.saved-jobs.*') ? 'text-[#464d79]' : 'text-neutral-600 hover:text-neutral-900' }} transition-colors">Saved Jobs</a>
                    @endauth
                </div>

                {{-- Right --}}
                <div class="flex items-center gap-3">
                    @auth
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('profile.show') }}" wire:navigate class="text-sm font-medium text-neutral-600 hover:text-[#464d79] transition-colors">My Profile</a>
                        <form method="POST" action="{{ route('jobseeker.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-neutral-500 hover:text-red-600 transition-colors">Logout</button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('jobseeker.login') }}" class="text-sm font-medium text-[#464d79] hover:underline">Login</a>
                    <a href="{{ route('jobseeker.register') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);">Register</a>
                    @endauth
                    <button @click="mobileMenu = !mobileMenu" class="sm:hidden w-9 h-9 flex items-center justify-center rounded-lg text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-transition class="sm:hidden border-t border-neutral-200 dark:border-neutral-800 px-4 pb-4 pt-2 space-y-2" style="display:none;">
            <a href="{{ route('jobs.index') }}" wire:navigate class="block py-2 text-sm text-neutral-700">Jobs</a>
            @auth
            <a href="{{ route('jobseeker.applications.index') }}" wire:navigate class="block py-2 text-sm text-neutral-700">My Applications</a>
            <a href="{{ route('jobseeker.saved-jobs.index') }}" wire:navigate class="block py-2 text-sm text-neutral-700">Saved Jobs</a>
            @endauth
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-12" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-white/70">&copy; {{ date('Y') }} Vaidyog. All rights reserved.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('terms') }}" class="text-sm text-white/70 hover:text-white transition-colors">Terms of Use</a>
                    <a href="{{ route('privacy') }}" class="text-sm text-white/70 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('disclaimer') }}" class="text-sm text-white/70 hover:text-white transition-colors">Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
