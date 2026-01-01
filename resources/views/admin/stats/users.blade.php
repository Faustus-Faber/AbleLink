@extends('layouts.admin')

@section('admin-content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">{{ $title }}</h1>
            <p class="text-slate-500 mt-1">
                Total: <span class="font-bold text-slate-900">{{ $count }}</span>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            {{-- ✅ Add button (use $createUrl passed from controller) --}}
            <a href="{{ $createUrl }}"
               class="inline-flex justify-center px-5 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                + Add
            </a>

            {{-- Search --}}
            <form method="GET" class="flex gap-2">
                <input name="q" value="{{ $search }}" placeholder="Search name or email…"
                       class="w-72 max-w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                <button class="px-5 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    Search
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left">
                        <th class="px-6 py-4 font-extrabold text-slate-700">Name</th>
                        <th class="px-6 py-4 font-extrabold text-slate-700">Email</th>
                        <th class="px-6 py-4 font-extrabold text-slate-700">Role</th>
                        <th class="px-6 py-4 font-extrabold text-slate-700 text-right">Remove</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold border border-slate-200">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                      onsubmit="return confirm('Remove this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 font-medium">
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection