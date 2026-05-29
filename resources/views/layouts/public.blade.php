<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', \App\Models\SiteSetting::get('meta_title', 'Vaidyog')) — Healthcare Jobs India</title>
    <meta name="description" content="@yield('description', \App\Models\SiteSetting::get('meta_description', 'Find healthcare jobs across India — doctors, nurses, allied health professionals. Vaidyog connects you with top hospitals and clinics.'))"/>
    @if(\App\Models\SiteSetting::get('meta_keywords'))
    <meta name="keywords" content="{{ \App\Models\SiteSetting::get('meta_keywords') }}"/>
    @endif
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>
    <link rel="canonical" href="{{ url()->current() }}"/>

    {{-- Google Search Console Verification --}}
    @if(\App\Models\SiteSetting::get('google_search_console'))
    <meta name="google-site-verification" content="{{ \App\Models\SiteSetting::get('google_search_console') }}"/>
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="@yield('og_title', 'Vaidyog — Healthcare Jobs India')"/>
    <meta property="og:description" content="@yield('og_description', 'India\'s #1 healthcare job portal for doctors, nurses & allied health.')"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))"/>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')

    {{-- Structured Data --}}
    @include('partials.schema.organization')
    @include('partials.schema.website')
    @stack('schema')

    {{-- Google Analytics --}}
    @if(\App\Models\SiteSetting::get('google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\Models\SiteSetting::get('google_analytics_id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ \App\Models\SiteSetting::get('google_analytics_id') }}');</script>
    @endif
</head>
<body class="bg-white font-sans text-gray-800 antialiased">
    @include('partials.navbar')

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @include('partials.footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
