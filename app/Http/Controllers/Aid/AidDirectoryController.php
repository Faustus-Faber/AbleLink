<?php

namespace App\Http\Controllers\Aid;

// F20 - Akida Lisi

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AidDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $tableReady = Schema::hasTable('aid_programs');

        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $region = trim((string) $request->query('region', ''));

        $programs = collect();
        $categories = collect();
        $regions = collect();

        if ($tableReady) {
            $base = DB::table('aid_programs')
                ->where('is_active', true);

            if ($q !== '') {
                $base->where(function ($qb) use ($q) {
                    $qb->where('title', 'like', "%{$q}%")
                        ->orWhere('agency', 'like', "%{$q}%")
                        ->orWhere('summary', 'like', "%{$q}%")
                        ->orWhere('eligibility', 'like', "%{$q}%")
                        ->orWhere('benefits', 'like', "%{$q}%")
                        ->orWhere('how_to_apply', 'like', "%{$q}%");
                });
            }

            if ($category !== '') {
                $base->where('category', $category);
            }

            if ($region !== '') {
                $base->where('region', $region);
            }

            $programs = $base
                ->orderBy('title')
                ->paginate(12)
                ->withQueryString();

            $categories = DB::table('aid_programs')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            $regions = DB::table('aid_programs')
                ->whereNotNull('region')
                ->where('region', '!=', '')
                ->distinct()
                ->orderBy('region')
                ->pluck('region');
        }

        return view('aid.index', [
            'tableReady' => $tableReady,
            'q' => $q,
            'category' => $category,
            'region' => $region,
            'categories' => $categories,
            'regions' => $regions,
            'programs' => $programs,
        ]);
    }

    public function show(string $slug): View
    {
        abort_unless(Schema::hasTable('aid_programs'), 404);

        $program = DB::table('aid_programs')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->first();

        abort_unless($program, 404);

        return view('aid.show', [
            'program' => $program,
        ]);
    }
}
