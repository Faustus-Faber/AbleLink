@extends('layouts.admin')

@section('admin-content')
@php
    $isEdit = ($mode ?? 'create') === 'edit';
    $p = $program;
    $tagsValue = old('tags');
    if ($tagsValue === null && $p && !empty($p->tags)) {
        $decoded = json_decode($p->tags, true);
        $tagsValue = is_array($decoded) ? implode(', ', $decoded) : '';
    }
@endphp

<div class="max-w-4xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">
                {{ $isEdit ? 'Edit Aid Program' : 'Create Aid Program' }}
            </h1>
            <p class="text-slate-600 mt-1">This controls what appears in the public Aid Directory.</p>
        </div>
        <a href="{{ route('admin.aid.index') }}" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-red-800 font-bold">
            <div class="font-extrabold">Please fix the errors below.</div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form method="POST" action="{{ $isEdit ? route('admin.aid.update', $p->id) : route('admin.aid.store') }}">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="title">Title</label>
                    <input id="title" name="title" value="{{ old('title', $p->title ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('title')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="slug">Slug (optional)</label>
                    <input id="slug" name="slug" value="{{ old('slug', $p->slug ?? '') }}" placeholder="auto-generated if blank"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('slug')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="agency">Agency</label>
                    <input id="agency" name="agency" value="{{ old('agency', $p->agency ?? '') }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('agency')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="category">Category</label>
                    <input id="category" name="category" value="{{ old('category', $p->category ?? '') }}" placeholder="e.g., Housing, Food, Disability"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('category')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="region">Region</label>
                    <input id="region" name="region" value="{{ old('region', $p->region ?? '') }}" placeholder="e.g., National, California"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('region')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="summary">Summary</label>
                    <textarea id="summary" name="summary" rows="3"
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('summary', $p->summary ?? '') }}</textarea>
                    @error('summary')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="eligibility">Eligibility</label>
                    <textarea id="eligibility" name="eligibility" rows="4"
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('eligibility', $p->eligibility ?? '') }}</textarea>
                    @error('eligibility')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="benefits">Benefits</label>
                    <textarea id="benefits" name="benefits" rows="4"
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('benefits', $p->benefits ?? '') }}</textarea>
                    @error('benefits')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="how_to_apply">How to apply</label>
                    <textarea id="how_to_apply" name="how_to_apply" rows="4"
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('how_to_apply', $p->how_to_apply ?? '') }}</textarea>
                    @error('how_to_apply')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="application_url">Application URL</label>
                    <input id="application_url" name="application_url" value="{{ old('application_url', $p->application_url ?? '') }}" placeholder="https://…"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('application_url')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="contact_phone">Contact phone</label>
                    <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $p->contact_phone ?? '') }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('contact_phone')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="contact_email">Contact email</label>
                    <input id="contact_email" name="contact_email" value="{{ old('contact_email', $p->contact_email ?? '') }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('contact_email')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="tags">Tags (optional)</label>
                    <input id="tags" name="tags" value="{{ $tagsValue ?? '' }}" placeholder="comma-separated, e.g., disability, housing, seniors"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('tags')<div class="text-sm text-red-700 mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" type="checkbox" name="is_active" value="1"
                           @checked(old('is_active', $p->is_active ?? true))>
                    <label for="is_active" class="text-sm font-bold text-slate-700">Active (visible in public directory)</label>
                    @error('is_active')<div class="text-sm text-red-700">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    {{ $isEdit ? 'Save changes' : 'Create program' }}
                </button>
                <a href="{{ route('admin.aid.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
