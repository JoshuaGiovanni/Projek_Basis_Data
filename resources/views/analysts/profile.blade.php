@extends('layouts.app')

@section('content')
<a href="{{ route('analyst.dashboard') }}" class="text-sm text-gray-500">← Back</a>

<h2 class="mt-2 text-2xl font-semibold">Analyst Profile</h2>

<form class="mt-4 grid gap-6" method="POST" action="{{ route('analysts.profile.save') }}">
    @csrf
    <section class="rounded-xl border bg-white p-6">
        <h3 class="font-semibold">Personal Information</h3>
        <p class="text-sm text-gray-600">Tell clients about yourself and your professional background</p>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">Full Name</label>
                <input name="full_name" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="Jane Doe" value="{{ old('full_name', $profile->full_name ?? auth()->user()->username) }}" />
            </div>
            <div>
                <label class="block text-sm font-medium">Professional Title</label>
                <input name="professional_title" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="Senior Data Scientist" />
            </div>
            
            <div>
                <label class="block text-sm font-medium">Years of Experience</label>
                <select name="years_of_experience" class="mt-1 w-full rounded-md border px-3 py-2">
                    <option value="0">0</option>
                    <option value="2">2</option>
                    <option value="4">4</option>
                    <option value="6">6</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Professional Description</label>
                <textarea name="description" class="mt-1 w-full rounded-md border px-3 py-2" rows="3" placeholder="Describe your expertise, specializations, and what makes you unique as a data analyst...">{{ old('description', $profile->description ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Max Ongoing Projects</label>
                <input name="max_ongoing_orders" type="number" min="1" max="50" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="5" value="{{ old('max_ongoing_orders', $profile->max_ongoing_orders ?? 5) }}" />
            </div>
        </div>
    </section>

    <section class="rounded-xl border bg-white p-6">
        <h3 class="font-semibold">Skills & Expertise</h3>
        <p class="text-sm text-gray-600">Add your technical skills and areas of expertise</p>
        <div class="mt-3 flex items-center gap-2">
            <input name="skills" class="w-full rounded-md border px-3 py-2" placeholder="Comma separated: Python, Machine Learning" />
            <button class="rounded-md border px-3 py-2">➕</button>
        </div>
        <div class="mt-3 text-sm text-gray-600">None added yet</div>
    </section>

    <div class="flex justify-end">
        <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-white">Save Profile</button>
    </div>
</form>
@endsection



