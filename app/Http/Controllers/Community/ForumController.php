<?php

//F13 - Farhan Zarif
namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;

use App\Models\Community\ForumThread;
use App\Models\Community\ForumReply;
use App\Services\Ai\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    protected $aiModerationService;

    public function __construct(AiModerationService $aiModerationService)
    {
        $this->aiModerationService = $aiModerationService;
    }

    public function index(Request $request)
    {
        $query = ForumThread::where('status', 'active');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $threads = $query->with('user')->withCount('replies')->orderBy('created_at', 'desc')->paginate(10);
        return view('forum.index', compact('threads'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category' => 'required',
        ]);

        $status = 'active';
        $flagReason = null;

        if (!$this->aiModerationService->isSafe($request->body) || !$this->aiModerationService->isSafe($request->title)) {
            $status = 'flagged';
            $r1 = $this->aiModerationService->getFlagReason($request->title);
            $r2 = $this->aiModerationService->getFlagReason($request->body);
            
            // Deduplicate reasons
            $reasons = array_filter([$r1, $r2]);
            $uniqueReasons = array_unique($reasons);
            $flagReason = implode('; ', $uniqueReasons) ?: 'Content flagged by AI.';
        }

        ForumThread::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
            'status' => $status,
            'flag_reason' => $flagReason,
        ]);

        if ($status === 'flagged') {
            return redirect()->route('forum.index')->with('warning', 'Your thread has been flagged for review.');
        }

        return redirect()->route('forum.index')->with('success', 'Thread created successfully.');
    }

    public function show($id)
    {
        $thread = ForumThread::with(['replies' => function ($query) {
            $query->where('status', 'active');
        }, 'replies.user'])->findOrFail($id);

        if ($thread->status !== 'active' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('forum.show', compact('thread'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['body' => 'required']);

        $status = 'active';
        $flagReason = null;

        if (!$this->aiModerationService->isSafe($request->body)) {
            $status = 'flagged';
            $flagReason = $this->aiModerationService->getFlagReason($request->body) ?: 'Content flagged by AI.';
        }

        ForumReply::create([
            'forum_thread_id' => $id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'status' => $status,
            'flag_reason' => $flagReason,
        ]);

        if ($status === 'flagged') {
            return back()->with('warning', 'Your reply has been flagged for review.');
        }

        return back()->with('success', 'Reply posted.');
    }

    public function destroy($id)
    {
        $thread = ForumThread::findOrFail($id);

        if (Auth::id() !== $thread->user_id) {
            abort(403);
        }

        $thread->delete();

        return redirect()->route('forum.index')->with('success', 'Thread deleted successfully.');
    }

    public function destroyReply($id)
    {
        $reply = ForumReply::findOrFail($id);

        if (Auth::id() !== $reply->user_id) {
            abort(403);
        }

        $reply->delete();

        return back()->with('success', 'Reply deleted successfully.');
    }
}


