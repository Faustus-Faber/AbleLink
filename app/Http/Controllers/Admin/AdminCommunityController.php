<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community\ForumThread;
use Illuminate\Http\Request;

/**
 * Manage community forum threads.
 */
class AdminCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ForumThread::with(['user', 'replies']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%") 
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $threads = $query->latest()->paginate(10);

        return view('admin.community.index', compact('threads'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForumThread $thread)
    {
        $thread->delete();

        return redirect()->back()->with('success', 'Thread deleted successfully.');
    }
}

