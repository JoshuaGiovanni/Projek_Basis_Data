<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DataMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen text-gray-200 selection:bg-blue-500/30 @yield('body_class', 'bg-[#162e71ff]')">
    <header class="sticky top-0 z-40 border-b border-white/10 bg-[#09101f]/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" aria-label="Open menu" class="inline-flex flex-col h-9 w-9 items-center justify-center focus:outline-none">
                    <span class="sr-only">Open menu</span>
                    <span class="block w-5 h-1 rounded-full hamburger-bar mb-1"></span>
                    <span class="block w-5 h-1 rounded-full hamburger-bar mb-1"></span>
                    <span class="block w-5 h-1 rounded-full hamburger-bar"></span>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="DataMate Logo"
                    class="h-12 w-auto brightness-110 drop-shadow-[0_0_10px_#2563EB] transition-transform duration-300 group-hover:scale-105 group-hover:drop-shadow-[0_0_18px_#3B82F6]" />
                <span class="text-xl font-bold tracking-tight text-white transition-all duration-300 group-hover:text-blue-400">
                    DataMate
                </span>
            </a>
            </div>
            <nav class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm text-gray-300 hover:text-white border-white/0 hover:border-white/100 border-b-2">Sign In</a>
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-blue-500/20 border-2 border-blue-500 px-3 py-1.5 text-sm text-blue-300 hover:bg-blue-500/30 transition">Sign Up</a>
            </nav>
        </div>
    </header>

    <div id="sidebarBackdrop" class="fixed inset-0 z-40 hidden bg-black/50"></div>
    <aside id="sidebar" class="fixed left-0 top-0 z-50 h-full w-72 -translate-x-full transform bg-[#0b0d11] border-r border-white/10 transition-transform">
        <div class="px-4 py-4 flex items-center justify-between border-b border-white/10">
            <div class="flex items-center gap-2 text-white font-semibold">
                <img src="{{ asset('images/logo.png') }}" alt="DataMate Logo"
                    class="h-12 w-auto brightness-110 drop-shadow-[0_0_10px_#2563EB] transition-transform duration-300 group-hover:scale-105 group-hover:drop-shadow-[0_0_18px_#3B82F6]" />
                <span>Menu</span>
            </div>
            <button id="sidebarClose" aria-label="Close menu" class="inline-flex h-8 w-8 items-center justify-center rounded-md hover:bg-white/10">
                <span class="block w-4 h-0.5 bg-white rotate-45 translate-y-0.5"></span>
                <span class="block w-4 h-0.5 bg-white -rotate-45 -translate-y-0.5"></span>
            </button>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('home') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Home</a>
            @auth
                @php($role = auth()->user()->role ?? null)
                @if($role === 'CLIENT')
                    <a href="{{ route('client.dashboard') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('analysts.index') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Find Analysts</a>
                @elseif($role === 'ANALYST')
                    <a href="{{ route('analyst.dashboard') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('analysts.profile') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Profile</a>
                @endif
            @endauth
            @guest
                <a href="{{ route('login') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Sign In</a>
                <a href="{{ route('register') }}" class="block rounded px-3 py-2 text-sm text-gray-200 hover:bg-white/10">Sign Up</a>
            @endguest
        </nav>
    </aside>

    <main class="mx-auto max-w-7xl px-6 py-[10px]">
        @yield('content')
    </main>

    <footer class="py-10 border-t border-white/10 text-center text-sm text-gray-400 hover:text-white transition">© {{ date('Y') }} DataMate</footer>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>