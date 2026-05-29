<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true' }" x-init="$watch('dark', val => { localStorage.setItem('dark', val); document.documentElement.classList.toggle('dark', val) }); document.documentElement.classList.toggle('dark', dark)" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Login — Vaidyog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class="antialiased">
    <livewire:admin.auth.admin-login />
</body>
</html>
