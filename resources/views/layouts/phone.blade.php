<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f8f6">
    <title>@yield('title', 'PawonSync') · PawonSync</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#e7ece9] font-sans text-ink antialiased">
    <div class="mx-auto flex min-h-screen w-full max-w-[430px] flex-col bg-mist shadow-[0_0_60px_rgba(6,48,31,0.12)] sm:my-6 sm:min-h-[calc(100vh-3rem)] sm:overflow-hidden sm:rounded-[34px]">
        @yield('content')
    </div>
</body>
</html>
