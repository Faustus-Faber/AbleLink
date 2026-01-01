@extends('layouts.admin')

@section('admin-content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-extrabold text-slate-900">{{ $title }}</h1>
    <p class="text-slate-500 mt-1">Total</p>

    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
        <div class="text-6xl font-extrabold text-slate-900">{{ $count }}</div>
    </div>
</div>
@endsection