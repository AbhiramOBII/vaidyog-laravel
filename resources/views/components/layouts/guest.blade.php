<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $pageTitle ?? 'Vaidyog' }} — Healthcare Jobs India</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    @livewireStyles
</head>
<body class="antialiased bg-neutral-50 min-h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
