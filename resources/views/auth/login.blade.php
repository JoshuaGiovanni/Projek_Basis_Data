@extends('layouts.app')

@section('content')
<a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition">← Back to Home</a>


<div class="mx-auto mt-6 max-w-lg rounded-2xl border border-white/10 bg-white/5 p-10 shadow-lg backdrop-blur-sm">
    <h2 class="text-center text-3xl font-semibold text-white">Welcome Back</h2>
    <p class="mt-1 text-center text-gray-400">Sign in to your DataMate account</p>

    <form class="mt-8 space-y-5" method="POST" action="{{ route('login.post') }}">
        @csrf
        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-300">Email</label>
            <input name="identifier" type="text"
                class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                placeholder="john@example.com" required />
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-300">Password</label>
            <div class="relative mt-1">
                <input id="passwordInput" name="password" type="password"
                    class="w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 pr-10 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                    placeholder="Enter your password" required />
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-400 focus:outline-none">
                    <!-- Eye Icon -->
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full rounded-lg bg-gray-900 py-3 text-white transition hover:bg-gray-800">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Sign up here</a>
    </p>
</div>

<!-- Password Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', (e) => {
        e.preventDefault();
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        // Update the icon by changing innerHTML instead of replacing the element
        eyeIcon.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round"
                d="M3.98 8.223a10.477 10.477 0 00-1.518 3.777 10.477 10.477 0 0018.495 3.294M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
               <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3l18 18" />`
            : `<path stroke-linecap="round" stroke-linejoin="round"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
               <circle cx="12" cy="12" r="3" />`;
    });
});
</script>
@endsection
