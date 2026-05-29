<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true' }" x-init="$watch('dark', val => { localStorage.setItem('dark', val); document.documentElement.classList.toggle('dark', val) }); document.documentElement.classList.toggle('dark', dark)" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Vaidyog Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
