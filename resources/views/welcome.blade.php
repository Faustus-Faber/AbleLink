@extends('layouts.app')

@section('content')
<div class="text-center py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">AbleLink System Operational</h1>
    <p class="text-lg text-gray-600">Database connection: <span class="text-green-600 font-bold">Active</span></p>
    <p class="text-sm text-gray-500 mt-2">Environment: {{ app()->environment() }}</p>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="font-bold text-lg mb-2">Migrations Applied</h3>
            <p class="text-gray-700">Users, Profiles, and Caregiver tables are ready.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="font-bold text-lg mb-2">Layout Integration</h3>
            <p class="text-gray-700">Tailwind CSS & Blade Layout loaded successfully.</p>
        </div>
    </div>
</div>
@endsection
