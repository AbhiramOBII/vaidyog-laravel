<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Vaidyog') — Job Seeker App</title>
    @vite(['resources/css/app.css'])
    <style>
        .hide-scroll::-webkit-scrollbar{display:none}
        .hide-scroll{-ms-overflow-style:none;scrollbar-width:none}
        body{background:linear-gradient(135deg,#e8eaf6 0%,#e0f2f1 100%)}
    </style>
</head>
<body class="min-h-screen flex items-start justify-center py-8 px-4">

    {{-- Page nav (desktop helper) --}}
    <div class="fixed top-4 left-4 z-50 hidden sm:flex flex-col gap-1 bg-white/90 backdrop-blur rounded-2xl p-3 shadow-lg border border-neutral-100 text-xs">
        <p class="font-bold text-neutral-500 uppercase tracking-wider px-1 mb-1 text-[10px]">Pages</p>
        @foreach([['dashboard','Home'],['jobs','Jobs'],['applications','Applied'],['saved-jobs','Saved'],['profile','Profile'],['edit-profile','Edit Profile']] as [$slug,$label])
        @php $active = request()->is("mobile/job-seeker/{$slug}"); @endphp
        <a href="{{ url("mobile/job-seeker/{$slug}") }}" class="px-3 py-1.5 rounded-xl font-medium transition-colors {{ $active ? 'bg-[#464d79] text-white' : 'text-neutral-600 hover:bg-neutral-100' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- Phone Frame --}}
    <div class="w-full max-w-[390px] bg-neutral-50 rounded-[44px] overflow-hidden relative flex flex-col"
         style="min-height:844px;box-shadow:0 40px 80px rgba(0,0,0,.25),0 0 0 10px #1a1a1a,0 0 0 12px #333">

        {{-- Status Bar --}}
        <div class="bg-[#464d79] px-7 pt-4 pb-2 flex items-center justify-between shrink-0">
            <span class="text-white text-xs font-semibold tracking-wide">9:41</span>
            <div class="flex items-center gap-1.5">
                <div class="flex items-end gap-px h-3">
                    <div class="w-1 h-1.5 bg-white rounded-sm"></div>
                    <div class="w-1 h-2 bg-white rounded-sm"></div>
                    <div class="w-1 h-2.5 bg-white rounded-sm"></div>
                    <div class="w-1 h-3 bg-white/40 rounded-sm"></div>
                </div>
                <svg class="w-3.5 h-2.5 text-white fill-current" viewBox="0 0 18 12">
                    <path d="M9 2.5C6.2 2.5 3.7 3.6 1.9 5.4L0 3.5C2.3 1.3 5.5 0 9 0s6.7 1.3 9.1 3.5L16.1 5.4C14.3 3.6 11.8 2.5 9 2.5z" opacity=".4"/>
                    <path d="M9 6.5c-1.8 0-3.4.7-4.6 1.9L2.8 6.8C4.4 5.1 6.6 4 9 4s4.6 1.1 6.2 2.8l-1.6 1.6C12.4 7.2 10.8 6.5 9 6.5z"/>
                    <circle cx="9" cy="11" r="1.5"/>
                </svg>
                <div class="flex items-center gap-px">
                    <div class="w-5 h-2.5 rounded-sm border border-white/70 p-px">
                        <div class="w-[75%] h-full bg-white rounded-sm"></div>
                    </div>
                    <div class="w-0.5 h-1.5 bg-white/50 rounded-r-sm"></div>
                </div>
            </div>
        </div>

        {{-- App Header (per-page) --}}
        @yield('app_header')

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto hide-scroll" style="padding-bottom:5.5rem">
            @yield('content')
        </div>

        {{-- Bottom Navigation --}}
        @php
            $rn = request()->route()?->getName() ?? '';
        @endphp
        <div class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-t border-neutral-100 px-1 pt-2 pb-7 z-10">
            <div class="flex items-center justify-around">

                @foreach([
                    ['mobile.jobseeker.dashboard',  'dashboard',    'Home',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['mobile.jobseeker.jobs',        'jobs',         'Jobs',    'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['mobile.jobseeker.applications','applications', 'Applied', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['mobile.jobseeker.saved-jobs',  'saved',        'Saved',   'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
                    ['mobile.jobseeker.profile',     'profile',      'Profile', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ] as [$routeName, $key, $label, $path])
                @php $isActive = str_contains($rn, $key); @endphp
                <a href="{{ route($routeName) }}" class="flex flex-col items-center gap-0.5 px-3 py-1 transition-all">
                    <div class="{{ $isActive ? 'bg-[#464d79]/10 p-1.5 rounded-xl' : 'p-1.5' }}">
                        <svg class="w-5 h-5 {{ $isActive ? 'text-[#464d79]' : 'text-neutral-400' }}" fill="none" stroke="currentColor" stroke-width="{{ $isActive ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-{{ $isActive ? 'bold' : 'medium' }} {{ $isActive ? 'text-[#464d79]' : 'text-neutral-400' }}">{{ $label }}</span>
                </a>
                @endforeach

            </div>
        </div>
    </div>
</body>
</html>
