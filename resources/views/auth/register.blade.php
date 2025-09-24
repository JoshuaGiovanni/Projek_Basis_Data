@extends('layouts.app')

@section('content')
<a href="{{ route('home') }}" class="text-sm text-gray-500">← Back to Home</a>

<div class="mx-auto mt-6 max-w-2xl rounded-2xl border bg-white p-10 shadow-sm">
    <h2 class="text-2xl font-semibold">Create your account</h2>
    <p class="text-gray-600">Choose your role to get the right experience.</p>

    <form class="mt-6 grid gap-6 md:grid-cols-2" method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium">Full Name</label>
                <input name="name" type="text" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="Jane Doe" value="{{ old('name') }}" required />
                @error('name')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input name="email" type="email" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="jane@example.com" value="{{ old('email') }}" required />
                @error('email')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input name="password" type="password" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="••••••••" required />
                @error('password')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="mt-1 w-full rounded-md border px-3 py-2" required>
                    <option value="CLIENT" {{ old('role') === 'CLIENT' ? 'selected' : '' }}>Client</option>
                    <option value="ANALYST" {{ old('role') === 'ANALYST' ? 'selected' : '' }}>Analyst</option>
                </select>
                @error('role')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Phone (optional)</label>
                <input name="phone" type="text" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="+62..." value="{{ old('phone') }}" />
                @error('phone')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="w-full rounded-md bg-gray-900 py-2 text-white">Create Account</button>
        </div>

        <div class="rounded-lg border bg-gray-50 p-4 text-sm text-gray-600">
            <div class="font-medium mb-2">What happens next?</div>
            <ul class="list-disc list-inside space-y-1">
                <li>We create your account securely</li>
                <li>Analysts will be taken to profile setup</li>
                <li>Clients can start browsing analysts</li>
            </ul>
        </div>
    </form>
</div>
@endsection


