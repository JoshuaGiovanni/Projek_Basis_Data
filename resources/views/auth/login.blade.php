@extends('layouts.app')

@section('content')
<a href="{{ route('home') }}" class="text-sm text-gray-500">← Back to Home</a>

<div class="mx-auto mt-6 max-w-lg rounded-2xl border bg-white p-10 shadow-sm">
    <h2 class="text-center text-2xl font-semibold">Welcome Back</h2>
    <p class="mt-1 text-center text-gray-600">Sign in to your DataMate account</p>

    <form class="mt-8 space-y-4" method="POST" action="{{ route('login.post') }}">
        @csrf
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input name="identifier" type="text" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="john@example.com or 1001" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input name="password" type="password" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="Enter your password" required />
        </div>
        <button type="submit" class="w-full rounded-lg bg-gray-900 py-3 text-white">Sign In</button>
    </form>

    <div class="mt-6 rounded-md bg-gray-50 p-4 text-sm text-gray-600">
        <div class="font-medium">Demo Accounts:</div>
        <div>Client: john@example.com / password123</div>
        <div>Analyst: sarah@example.com / password123</div>
    </div>

    <p class="mt-6 text-center text-sm">Don't have an account? <a href="{{ route('register') }}" class="underline">Sign up here</a></p>
</div>
@endsection


