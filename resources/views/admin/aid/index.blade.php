@extends('layouts.admin')

@section('admin-content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Admin · Aid Directory</h1>
            <p class="text-slate-600 mt-1">Create and maintain the searchable government aid directory.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
                Admin Dashboard
            </a>
            @if($tableReady)
                <a href="{{ route('admin.aid.create') }}" class="px-5 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    + Add program
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-800 font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if(!$tableReady)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900">
            <p class="font-bold">Aid Directory is not initialized yet.</p>
            <p class="text-sm mt-1">Run migrations to create the <code class="font-mono">aid_programs</code> table.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <form method="GET" action="{{ route('admin.aid.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-7">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="q">Search</label>
                    <input id="q" name="q" value="{{ $q }}" placeholder="Search title, agency, category, region…"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="status">Status</label>
                    <div class="relative" x-data="{
                        status: '{{ $status }}',
                        open: false,
                        options: {
                            'active': 'Active',
                            'inactive': 'Inactive',
                            'all': 'All'
                        }
                    }">
                        <input type="hidden" name="status" :value="status">
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 flex items-center justify-between">
                            <span x-text="options[status]" class="font-medium text-slate-700"></span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-0 top-full mt-2 w-full bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50 py-1"
                             style="display: none;">
                            <template x-for="(label, key) in options" :key="key">
                                <button type="button"
                                        @click="status = key; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors"
                                        :class="{'bg-slate-50 text-indigo-600': status === key}">
                                    <span x-text="label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2 flex gap-3">
                    <button type="submit" class="w-full px-6 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-left">
                            <th class="px-6 py-4 font-extrabold text-slate-700">Program</th>
                            <th class="px-6 py-4 font-extrabold text-slate-700">Category</th>
                            <th class="px-6 py-4 font-extrabold text-slate-700">Region</th>
                            <th class="px-6 py-4 font-extrabold text-slate-700">Status</th>
                            <th class="px-6 py-4 font-extrabold text-slate-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($programs as $p)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-900">{{ $p->title }}</div>
                                    <div class="text-slate-500">{{ $p->agency }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-1">/{{ $p->slug }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $p->category }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $p->region }}</td>
                                <td class="px-6 py-4">
                                    @if($p->is_active)
                                        <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 font-bold border border-green-100">Active</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold border border-slate-200">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('aid.show', $p->slug) }}" target="_blank"
                                           class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition-all">
                                            View
                                        </a>
                                        <a href="{{ route('admin.aid.edit', $p->id) }}"
                                           class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.aid.toggle', $p->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-xl bg-white text-slate-900 font-bold border border-slate-200 hover:bg-slate-50 transition-all">
                                                Toggle
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.aid.destroy', $p->id) }}"
                                              onsubmit="return confirm('Delete this program? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500 font-medium">
                                    No aid programs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $programs->links() }}
        </div>
    @endif
</div>
@endsection
