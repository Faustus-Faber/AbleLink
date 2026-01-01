@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Government Aid Directory</h1>
            <p class="text-slate-600 mt-1">Search programs, benefits, grants, and services.</p>
        </div>
        <a href="{{ route('admin.login') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">
            Admin
        </a>
    </div>

    @if(!$tableReady)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900">
            <p class="font-bold">Aid Directory is not initialized yet.</p>
            <p class="text-sm mt-1">Run the database migrations to create the <code class="font-mono">aid_programs</code> table.</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mt-6">
        <form method="GET" action="{{ route('aid.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-6">
                <label class="block text-sm font-bold text-slate-700 mb-2" for="q">Search</label>
                <input id="q" name="q" value="{{ $q }}" placeholder="e.g., disability benefits, housing, food support"
                       class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-slate-700 mb-2" for="category">Category</label>
                <select id="category" name="category"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" @selected($category === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-slate-700 mb-2" for="region">Region</label>
                <select id="region" name="region"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All</option>
                    @foreach($regions as $r)
                        <option value="{{ $r }}" @selected($region === $r)>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-12 flex gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    Search
                </button>
                <a href="{{ route('aid.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="mt-8">
        @if($tableReady && $programs->count() === 0)
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-slate-700">
                <p class="font-bold">No programs found.</p>
                <p class="text-sm mt-1">Try broadening your search terms, or remove filters.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($programs as $p)
                <a href="{{ route('aid.show', $p->slug) }}" class="block bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-extrabold text-slate-900 truncate">{{ $p->title }}</h3>
                            <p class="text-sm text-slate-600 mt-1">
                                @if($p->agency)
                                    <span class="font-bold">{{ $p->agency }}</span>
                                @endif
                                @if($p->category)
                                    <span class="mx-2 text-slate-300">|</span>
                                    <span>{{ $p->category }}</span>
                                @endif
                                @if($p->region)
                                    <span class="mx-2 text-slate-300">|</span>
                                    <span>{{ $p->region }}</span>
                                @endif
                            </p>
                            @if($p->summary)
                                <p class="text-sm text-slate-700 mt-3">{{ $p->summary }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-slate-400 font-bold">→</div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($tableReady)
            <div class="mt-8">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
