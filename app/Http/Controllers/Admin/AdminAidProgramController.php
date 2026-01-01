<?php

namespace App\Http\Controllers\Admin;

// F20 - Akida Lisi

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminAidProgramController extends Controller
{
    public function index(Request $request): View
    {
        $tableReady = Schema::hasTable('aid_programs');

        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', 'active')); // active|inactive|all

        $programs = collect();

        if ($tableReady) {
            $base = DB::table('aid_programs');

            if ($status === 'active') {
                $base->where('is_active', true);
            } elseif ($status === 'inactive') {
                $base->where('is_active', false);
            }

            if ($q !== '') {
                $base->where(function ($qb) use ($q) {
                    $qb->where('title', 'like', "%{$q}%")
                        ->orWhere('agency', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%")
                        ->orWhere('region', 'like', "%{$q}%")
                        ->orWhere('summary', 'like', "%{$q}%");
                });
            }

            $programs = $base
                ->orderByDesc('updated_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.aid.index', [
            'tableReady' => $tableReady,
            'q' => $q,
            'status' => $status,
            'programs' => $programs,
        ]);
    }

    public function create(): View
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        return view('admin.aid.form', [
            'mode' => 'create',
            'program' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        $validated = $this->validatePayload($request);

        $slug = $this->uniqueSlug(
            $validated['slug'] ?: Str::slug($validated['title'])
        );

        DB::table('aid_programs')->insert([
            'slug' => $slug,
            'title' => $validated['title'],
            'agency' => $validated['agency'],
            'category' => $validated['category'],
            'region' => $validated['region'],
            'summary' => $validated['summary'],
            'eligibility' => $validated['eligibility'],
            'benefits' => $validated['benefits'],
            'how_to_apply' => $validated['how_to_apply'],
            'application_url' => $validated['application_url'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'is_active' => (bool) $validated['is_active'],
            'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.aid.index')
            ->with('success', 'Aid program created.');
    }

    public function edit(int $id): View
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        $program = DB::table('aid_programs')->where('id', $id)->first();
        abort_unless($program, 404);

        return view('admin.aid.form', [
            'mode' => 'edit',
            'program' => $program,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        $program = DB::table('aid_programs')->where('id', $id)->first();
        abort_unless($program, 404);

        $validated = $this->validatePayload($request);

        $slugInput = $validated['slug'] ?: (string) $program->slug;
        $slug = $this->uniqueSlug($slugInput, $id);

        DB::table('aid_programs')
            ->where('id', $id)
            ->update([
                'slug' => $slug,
                'title' => $validated['title'],
                'agency' => $validated['agency'],
                'category' => $validated['category'],
                'region' => $validated['region'],
                'summary' => $validated['summary'],
                'eligibility' => $validated['eligibility'],
                'benefits' => $validated['benefits'],
                'how_to_apply' => $validated['how_to_apply'],
                'application_url' => $validated['application_url'],
                'contact_phone' => $validated['contact_phone'],
                'contact_email' => $validated['contact_email'],
                'is_active' => (bool) $validated['is_active'],
                'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.aid.index')
            ->with('success', 'Aid program updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        DB::table('aid_programs')->where('id', $id)->delete();

        return redirect()
            ->route('admin.aid.index')
            ->with('success', 'Aid program deleted.');
    }

    public function toggle(int $id): RedirectResponse
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        $program = DB::table('aid_programs')->where('id', $id)->first();
        abort_unless($program, 404);

        DB::table('aid_programs')
            ->where('id', $id)
            ->update([
                'is_active' => !((bool) $program->is_active),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.aid.index')
            ->with('success', 'Aid program status updated.');
    }

    /**
     * @return array{
     *   title:string,
     *   slug:?string,
     *   agency:?string,
     *   category:?string,
     *   region:?string,
     *   summary:?string,
     *   eligibility:?string,
     *   benefits:?string,
     *   how_to_apply:?string,
     *   application_url:?string,
     *   contact_phone:?string,
     *   contact_email:?string,
     *   is_active:bool,
     *   tags:array<int,string>
     * }
     */
    private function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'agency' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'eligibility' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'how_to_apply' => ['nullable', 'string'],
            'application_url' => ['nullable', 'url', 'max:2048'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'string', 'max:1000'], // comma-separated
        ]);

        $tags = [];
        if (!empty($validated['tags'])) {
            $tags = collect(explode(',', (string) $validated['tags']))
                ->map(fn ($t) => trim($t))
                ->filter(fn ($t) => $t !== '')
                ->unique()
                ->values()
                ->all();
        }

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'agency' => $validated['agency'] ?? null,
            'category' => $validated['category'] ?? null,
            'region' => $validated['region'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'eligibility' => $validated['eligibility'] ?? null,
            'benefits' => $validated['benefits'] ?? null,
            'how_to_apply' => $validated['how_to_apply'] ?? null,
            'application_url' => $validated['application_url'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'tags' => $tags,
        ];
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($slug);
        if ($slug === '') {
            $slug = 'aid-program';
        }

        $base = $slug;
        $i = 2;

        while (true) {
            $exists = DB::table('aid_programs')
                ->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists();

            if (!$exists) {
                return $slug;
            }

            $slug = "{$base}-{$i}";
            $i++;
        }
    }
}
