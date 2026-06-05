@props(['pageTitle' => 'Admin'])
<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true', sidebarOpen: false }" x-init="$watch('dark', val => { localStorage.setItem('dark', val); document.documentElement.classList.toggle('dark', val) }); document.documentElement.classList.toggle('dark', dark)" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $pageTitle ?? 'Admin' }} — Vaidyog</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { brand: { 50:'#eef2ff', 100:'#e0e7ff', 500:'#6366f1', 600:'#4f46e5', 700:'#4338ca' }, accent: { 400:'#34d399', 500:'#10b981' } } } } }</script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .ck-editor__editable { min-height: 200px; }
        .sb-scroll::-webkit-scrollbar { width: 5px; }
        .sb-scroll::-webkit-scrollbar-track { background: transparent; }
        .sb-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 99px; }
        .sb-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.15); }
        .sb-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.08) transparent; }
        .nav-item { display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0.75rem; border-radius:0.75rem; font-size:13px; font-weight:500; transition:all 150ms; }
        .nav-active { background:rgba(255,255,255,0.12); color:#fff; box-shadow:inset 0 1px 0 rgba(255,255,255,0.08); }
        .nav-idle { color:#94a3b8; }
        .nav-idle:hover { color:#fff; background:rgba(255,255,255,0.06); }
        .section-label { display:flex; align-items:center; justify-content:space-between; padding:0.375rem 0.75rem; cursor:pointer; user-select:none; }
        .section-text { font-size:10px; text-transform:uppercase; letter-spacing:0.12em; font-weight:600; color:#64748b; }
        .section-chevron { width:0.875rem; height:0.875rem; color:#475569; transition:transform 200ms; }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 min-h-screen">

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-[260px] flex flex-col bg-slate-900 border-r border-white/[.06] transform transition-transform duration-200 ease-in-out"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 h-16 shrink-0">
            <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-9 w-auto">
            <div>
                <span class="text-white font-bold text-[15px] tracking-tight">Vaidyog</span>
                <p class="text-[10px] font-semibold tracking-[0.15em] uppercase text-brand-500">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 pt-2 pb-4 space-y-1 overflow-y-auto sb-scroll">

            {{-- Main --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-idle' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 8.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/></svg>
                Dashboard
            </a>

            {{-- People --}}
            @if(auth('admin')->user()->hasAnyPermission(['job_seekers.view', 'recruiters.view']))
            <div class="pt-4 mt-2" x-data="{ open: {{ request()->routeIs('admin.job-seekers.*') || request()->routeIs('admin.recruiters.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">People</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('job_seekers.view'))
                    <a href="{{ route('admin.job-seekers.index') }}" class="nav-item {{ request()->routeIs('admin.job-seekers.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        Job Seekers
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('recruiters.view'))
                    <a href="{{ route('admin.recruiters.index') }}" class="nav-item {{ request()->routeIs('admin.recruiters.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                        Recruiters
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Jobs --}}
            @if(auth('admin')->user()->hasAnyPermission(['jobs.view', 'jobs.approve', 'jobs.delete']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.jobs.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Jobs</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('jobs.view'))
                    <a href="{{ route('admin.jobs.index') }}" class="nav-item {{ request()->routeIs('admin.jobs.index') || request()->routeIs('admin.jobs.show') || request()->routeIs('admin.jobs.edit') || request()->routeIs('admin.jobs.create') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        All Jobs
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('jobs.approve'))
                    <a href="{{ route('admin.jobs.pending') }}" class="nav-item {{ request()->routeIs('admin.jobs.pending') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pending Approval
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('jobs.delete'))
                    <a href="{{ route('admin.jobs.bin') }}" class="nav-item {{ request()->routeIs('admin.jobs.bin') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Bin
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Applications --}}
            @if(auth('admin')->user()->hasAnyPermission(['applications.view', 'applications.delete']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.applications.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Applications</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('applications.view'))
                    <a href="{{ route('admin.applications.index') }}" class="nav-item {{ request()->routeIs('admin.applications.index') || request()->routeIs('admin.applications.show') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        All Applications
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('applications.delete'))
                    <a href="{{ route('admin.applications.bin') }}" class="nav-item {{ request()->routeIs('admin.applications.bin') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Application Bin
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Content --}}
            @if(auth('admin')->user()->hasAnyPermission(['news.view', 'events.view', 'blogs.view', 'faqs.manage', 'specialties.manage']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.news*') || request()->routeIs('admin.event*') || request()->routeIs('admin.blog*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.specialties.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Content</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('news.view'))
                    <a href="{{ route('admin.news-categories.index') }}" class="nav-item {{ request()->routeIs('admin.news-categories.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                        News Categories
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('news.view'))
                    <a href="{{ route('admin.news.index') }}" class="nav-item {{ request()->routeIs('admin.news.index') || request()->routeIs('admin.news.create') || request()->routeIs('admin.news.edit') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                        News Articles
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('events.view'))
                    <a href="{{ route('admin.event-categories.index') }}" class="nav-item {{ request()->routeIs('admin.event-categories.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                        Event Categories
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('events.view'))
                    <a href="{{ route('admin.events.index') }}" class="nav-item {{ request()->routeIs('admin.events.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/></svg>
                        Events
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('blogs.view'))
                    <a href="{{ route('admin.blog-categories.index') }}" class="nav-item {{ request()->routeIs('admin.blog-categories.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                        Blog Categories
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('blogs.view'))
                    <a href="{{ route('admin.blogs.index') }}" class="nav-item {{ request()->routeIs('admin.blogs.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        Blog Posts
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('faqs.manage'))
                    <a href="{{ route('admin.faqs.index') }}" class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                        FAQs
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('specialties.manage'))
                    <a href="{{ route('admin.specialties.index') }}" class="nav-item {{ request()->routeIs('admin.specialties.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.59-5.59a1.5 1.5 0 010-2.12l.88-.88a1.5 1.5 0 012.12 0l2.83 2.83 7.07-7.07a1.5 1.5 0 012.12 0l.88.88a1.5 1.5 0 010 2.12l-9.19 9.19a1.5 1.5 0 01-2.12.64z"/></svg>
                        Specialties
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Support --}}
            @if(auth('admin')->user()->hasAnyPermission(['feedbacks.view', 'support_tickets.view']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.feedbacks.*') || request()->routeIs('admin.support-tickets.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Support</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('feedbacks.view'))
                    <a href="{{ route('admin.feedbacks.index') }}" class="nav-item {{ request()->routeIs('admin.feedbacks.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                        Feedbacks
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('support_tickets.view'))
                    <a href="{{ route('admin.support-tickets.index') }}" class="nav-item {{ request()->routeIs('admin.support-tickets.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m0 0a3.765 3.765 0 010-2.528m2.268 4.796l-4.138 3.448m0 0a9.027 9.027 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.027 9.027 0 00-1.652 1.306c-.51.51-.944 1.064-1.306 1.652"/></svg>
                        Support Tickets
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Billing --}}
            @if(auth('admin')->user()->hasAnyPermission(['plans.view', 'plans.manage', 'payments.view', 'subscriptions.view', 'subscriptions.assign']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.plans.*') || request()->routeIs('admin.subscriptions.*') || request()->routeIs('admin.payments.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Billing</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('plans.view'))
                    <a href="{{ route('admin.plans.jobseeker.index') }}" class="nav-item {{ request()->routeIs('admin.plans.jobseeker.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        JS Plans
                    </a>
                    <a href="{{ route('admin.plans.recruiter.index') }}" class="nav-item {{ request()->routeIs('admin.plans.recruiter.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Recruiter Plans
                    </a>
                    <a href="{{ route('admin.plans.featured.index') }}" class="nav-item {{ request()->routeIs('admin.plans.featured.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        Featured Plans
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('subscriptions.assign'))
                    <a href="{{ route('admin.plans.assign.index') }}" class="nav-item {{ request()->routeIs('admin.plans.assign.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                        Assign Plan
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('subscriptions.view'))
                    <a href="{{ route('admin.subscriptions.jobseekers') }}" class="nav-item {{ request()->routeIs('admin.subscriptions.jobseekers') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        JS Subscriptions
                    </a>
                    <a href="{{ route('admin.subscriptions.recruiters') }}" class="nav-item {{ request()->routeIs('admin.subscriptions.recruiters') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rec Subscriptions
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('payments.view'))
                    <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Payments
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Team --}}
            @if(auth('admin')->user()->hasAnyPermission(['roles.view', 'sub_admins.view']))
            <div class="pt-4 mt-1" x-data="{ open: {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.sub-admins.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="section-label w-full">
                    <span class="section-text">Team</span>
                    <svg :class="open && 'rotate-180'" class="section-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-1">
                    @if(auth('admin')->user()->hasPermission('roles.view'))
                    <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        Roles
                    </a>
                    @endif
                    @if(auth('admin')->user()->hasPermission('sub_admins.view'))
                    <a href="{{ route('admin.sub-admins.index') }}" class="nav-item {{ request()->routeIs('admin.sub-admins.*') ? 'nav-active' : 'nav-idle' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                        Sub-Admins
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Settings --}}
            @if(auth('admin')->user()->hasPermission('settings.view'))
            <div class="pt-4 mt-1 border-t border-white/[.06]">
                <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'nav-active' : 'nav-idle' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Site Settings
                </a>
            </div>
            @endif
        </nav>

        {{-- User --}}
        <div class="px-3 py-3 border-t border-white/[.06] shrink-0">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-white/[.04]">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                    {{ substr(auth('admin')->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-white truncate">{{ auth('admin')->user()->name ?? 'Admin' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth('admin')->user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-white/10 transition-colors" title="Sign out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ==================== OVERLAY ==================== --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 lg:hidden" x-transition.opacity style="display:none;"></div>

    {{-- ==================== MAIN ==================== --}}
    <div class="lg:pl-[260px] min-h-screen flex flex-col">
        {{-- Top bar --}}
        <header class="sticky top-0 z-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800/60">
            <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white hidden lg:block">{{ $pageTitle ?? 'Admin' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/') }}" target="_blank" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        View Site
                    </a>
                    <button @click="dark = !dark" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg x-show="dark" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                        <svg x-show="!dark" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-8">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="px-4 lg:px-8 py-4 border-t border-slate-100 dark:border-slate-800/60">
            <p class="text-xs text-slate-400 dark:text-slate-600 text-center">&copy; {{ date('Y') }} Vaidyog. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
