@extends('layouts.app')

@section('content')
<a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition">← Back to Home</a>

<div class="mx-auto mt-8 max-w-xl rounded-2xl border border-white/10 bg-white/5 p-8 shadow-sm backdrop-blur-sm">
    <h2 class="text-3xl font-bold text-white">Create your account</h2>
    <p class="mt-2 text-gray-400">Choose your role to get the right experience.</p>

    <form class="mt-8 space-y-6" method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300">Full Name</label>
                <input name="username" type="text"
                       class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                       placeholder="Jane Doe" value="{{ old('username') }}" required />
                @error('username')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Email</label>
                <input name="email" type="email"
                       class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                       placeholder="jane@example.com" value="{{ old('email') }}" required />
                @error('email')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Password</label>
                <div class="relative mt-1">
                    <input id="passwordInput" name="password" type="password"
                           class="w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 pr-10 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                           placeholder="••••••••" required />
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
                @error('password')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Role</label>
                <select name="role" id="roleSelect"
                        class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        required>
                    <option value="CLIENT" {{ old('role') === 'CLIENT' ? 'selected' : '' }}>Client</option>
                    <option value="ANALYST" {{ old('role') === 'ANALYST' ? 'selected' : '' }}>Analyst</option>
                </select>
                @error('role')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>

            <div id="clientTypeField" class="hidden">
                <label class="block text-sm font-medium text-gray-300">Client Type</label>
                <select name="client_type"
                        class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    <option value="INDIVIDUAL" {{ old('client_type') === 'INDIVIDUAL' ? 'selected' : '' }}>Individual</option>
                    <option value="COMPANY" {{ old('client_type') === 'COMPANY' ? 'selected' : '' }}>Company</option>
                </select>
                @error('client_type')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
            
            <div id="experienceField" class="hidden">
                <label class="block text-sm font-medium text-gray-300">Years of Experience</label>
                <input name="years_of_experience" type="number" min="0"
                       class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                       placeholder="e.g. 3" value="{{ old('years_of_experience') }}" />
                @error('years_of_experience')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Phone</label>
                <input name="phone" type="text"
                       class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                       placeholder="+62..." value="{{ old('phone') }}" required/>
                @error('phone')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>
        </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Birthdate</label>
                <input name="birthdate" type="date"
                       class="mt-1 w-full rounded-md border border-white/10 bg-white/10 px-4 py-2 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                       value="{{ old('birthdate') }}" />
                @error('birthdate')<div class="mt-1 text-sm text-red-400">{{ $message }}</div>@enderror
            </div>

        <!-- Submit Button updated to match login -->
        <button type="submit" class="w-full rounded-lg bg-gray-900 py-3 text-white text-lg font-semibold transition hover:bg-gray-800">
            Create Account
        </button>
    </form>

    <div class="mt-8 rounded-lg border border-white/10 bg-white/5 p-4 text-sm text-gray-400">
        <div class="mb-2 font-medium text-white">What happens next?</div>
        <ul class="list-disc list-inside space-y-1">
            <li>We create your account securely.</li>
            <li>Analysts will be guided to set up their professional profile.</li>
            <li>Clients can immediately start browsing available analysts.</li>
        </ul>
    </div>
</div>

<!-- Password toggle script (matches login style) -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', (e) => {
        e.preventDefault();
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        // toggle icon paths
        eyeIcon.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round"
                  d="M3.98 8.223a10.477 10.477 0 00-1.518 3.777 10.477 10.477 0 0018.495 3.294M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
               <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 3l18 18" />`
            : `<path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
               <circle cx="12" cy="12" r="3" />`;
    });

    // Role toggle logic
    const roleSelect = document.getElementById('roleSelect');
    const experienceField = document.getElementById('experienceField');
    const clientTypeField = document.getElementById('clientTypeField');

    function toggleFields() {
        if (roleSelect.value === 'ANALYST') {
            experienceField.classList.remove('hidden');
            clientTypeField.classList.add('hidden');
        } else {
            experienceField.classList.add('hidden');
            clientTypeField.classList.remove('hidden');
        }
    }

    roleSelect.addEventListener('change', toggleFields);
    // Run on load in case validation failed and we're showing old input
    toggleFields();
});
</script>
@endsection
