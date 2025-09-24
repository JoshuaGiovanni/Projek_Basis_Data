@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1 class="text-5xl font-bold tracking-tight">Connect Data Analysts with Clients</h1>
    <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">Bridge the gap between skilled data analysts and businesses seeking insights. Join our platform to find the perfect match for your data needs.</p>

    <div class="mt-14 grid gap-8 md:grid-cols-2">
        <div class="rounded-2xl border bg-white p-10 shadow-sm">
            <h2 class="text-2xl font-bold">Create Account</h2>
            <ul class="mt-3 text-gray-600 text-mid list-disc list-inside">
                <li>Choose your role (Client or Analyst)</li>
                <li>Set up your profile</li>
                <li>Start connecting immediately</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 inline-flex rounded-lg bg-gray-900 px-6 py-3 text-white">Sign Up</a>
        </div>
        <div class="rounded-2xl border bg-white p-10 shadow-sm">
            <h2 class="text-2xl font-bold">Sign In</h2>
            <ul class="mt-3 text-gray-600 text-mid list-disc list-inside">
                <li>Access your dashboard</li>
                <li>Manage your profile</li>
                <li>Continue your connections</li>
            </ul>
            <a href="{{ route('login') }}" class="mt-8 inline-flex rounded-lg border border-gray-900 px-6 py-3">Sign In</a>
        </div>
    </div>
</div>
@endsection


