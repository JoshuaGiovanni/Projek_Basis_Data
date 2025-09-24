<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DataMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <header class="border-b bg-white">
        <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold">
                <span>DataMate</span>
            </a>
            <nav class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hover:underline">Sign In</a>
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-white">Sign Up</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        @yield('content')
    </main>

    <footer class="py-10 text-center text-sm text-gray-500">© {{ date('Y') }} DataMate</footer>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>


