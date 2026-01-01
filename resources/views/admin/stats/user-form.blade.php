@extends('layouts.admin')

@section('admin-content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Add {{ ucfirst($role) }}</h1>
            <p class="text-slate-500 mt-1">Create a new account and assign a role.</p>
        </div>
        <a href="{{ url()->previous() }}" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
            Back
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <input type="hidden" name="role" value="{{ $role }}">

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Name</label>
                    <input name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                    @error('name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                    @error('email') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input name="password" type="password" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                    @error('password') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button class="px-6 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection