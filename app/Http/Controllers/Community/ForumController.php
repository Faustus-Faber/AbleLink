<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\ForumThread;
use App\Models\Community\ForumReply;
use App\Services\Ai\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumController extends Controller
{
    private AiModerationService $aiModerationService;

    public function __construct(AiModerationService $aiModerationService)
    {
        $this->aiModerationService = $aiModerationService;
    }

    public function index(Request $incomingRequest): View
    {
        $threadQuery = ForumThread::where('status', 'active');

        $hasSearchTerm = $incomingRequest->has('search');
        if ($hasSearchTerm === true) {
            $searchTerm = $incomingRequest->input('search');
            $threadQuery->where(function(Builder $query) use ($searchTerm) {
                $titleConstraint = $query->where('title', 'like', "%{$searchTerm}%");
                $titleConstraint->orWhere('body', 'like', "%{$searchTerm}%");
            });
        }

        $threadQuery->with('user');
        $threadQuery->withCount('replies');
        $threadQuery->orderBy('created_at', 'desc');
        
        $paginatedThreads = $threadQuery->paginate(10);
        
        return view('forum.index', ['threads' => $paginatedThreads]);
    }

    public function create(): View
    {
        return view('forum.create');
    }

    public function store(Request $incomingRequest): RedirectResponse
    {
        $incomingRequest->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category' => 'required',
        ]);

        $threadStatus = 'active';
        $flagReasonString = null;

        $titleIsSafe = $this->aiModerationService->isSafe($incomingRequest->title);
        $bodyIsSafe = $this->aiModerationService->isSafe($incomingRequest->body);

        if ($titleIsSafe === false) {
             $threadStatus = 'flagged';
        } elseif ($bodyIsSafe === false) {
             $threadStatus = 'flagged';
        }

        if ($threadStatus === 'flagged') {
            $reasonOne = $this->aiModerationService->getFlagReason($incomingRequest->title);
            $reasonTwo = $this->aiModerationService->getFlagReason($incomingRequest->body);
            
            $reasonList = [];
            if ($reasonOne !== null) {
                $reasonList[] = $reasonOne;
            }
            if ($reasonTwo !== null) {
                $reasonList[] = $reasonTwo;
            }
            
            $uniqueReasonsList = array_unique($reasonList);
            $joinedReasons = implode('; ', $uniqueReasonsList);
            
            if ($joinedReasons === '') {
                $joinedReasons = 'Content flagged by AI.';
            }
            $flagReasonString = $joinedReasons;
        }

        $newThreadData = [];
        $newThreadData['user_id'] = Auth::id();
        $newThreadData['title'] = $incomingRequest->title;
        $newThreadData['body'] = $incomingRequest->body;
        $newThreadData['category'] = $incomingRequest->category;
        $newThreadData['status'] = $threadStatus;
        $newThreadData['flag_reason'] = $flagReasonString;

        ForumThread::create($newThreadData);

        if ($threadStatus === 'flagged') {
            return redirect()->route('forum.index')->with('warning', 'Your thread has been flagged for review.');
        }

        return redirect()->route('forum.index')->with('success', 'Thread created successfully.');
    }

    public function show(string $threadId): View
    {
        $threadQuery = ForumThread::with(['replies' => function (HasMany $query) {
            $query->where('status', 'active');
        }, 'replies.user']);
        
        $targetThread = $threadQuery->findOrFail($threadId);

        $isThreadActive = $targetThread->status === 'active';
        if ($isThreadActive === false) {
            $currentUser = Auth::user();
            $isAdmin = $currentUser->role === 'admin';
            
            if ($isAdmin === false) {
                abort(403);
            }
        }

        return view('forum.show', ['thread' => $targetThread]);
    }

    public function reply(Request $incomingRequest, string $threadId): RedirectResponse
    {
        $incomingRequest->validate(['body' => 'required']);

        $replyStatus = 'active';
        $flagReasonString = null;

        $isBodySafe = $this->aiModerationService->isSafe($incomingRequest->body);
        
        if ($isBodySafe === false) {
            $replyStatus = 'flagged';
            $reasonResult = $this->aiModerationService->getFlagReason($incomingRequest->body);
            
            if ($reasonResult !== null) {
                $flagReasonString = $reasonResult;
            } else {
                $flagReasonString = 'Content flagged by AI.';
            }
        }

        $newReplyData = [];
        $newReplyData['forum_thread_id'] = $threadId;
        $newReplyData['user_id'] = Auth::id();
        $newReplyData['body'] = $incomingRequest->body;
        $newReplyData['status'] = $replyStatus;
        $newReplyData['flag_reason'] = $flagReasonString;

        ForumReply::create($newReplyData);

        if ($replyStatus === 'flagged') {
            return back()->with('warning', 'Your reply has been flagged for review.');
        }

        return back()->with('success', 'Reply posted.');
    }

    public function destroy(string $threadId): RedirectResponse
    {
        $targetThread = ForumThread::findOrFail($threadId);
        $currentUserId = Auth::id();
        
        $isOwner = $currentUserId === $targetThread->user_id;
        if ($isOwner === false) {
            abort(403);
        }

        $targetThread->delete();

        return redirect()->route('forum.index')->with('success', 'Thread deleted successfully.');
    }

    public function destroyReply(string $replyId): RedirectResponse
    {
        $targetReply = ForumReply::findOrFail($replyId);
        $currentUserId = Auth::id();

        $isOwner = $currentUserId === $targetReply->user_id;
        if ($isOwner === false) {
            abort(403);
        }

        $targetReply->delete();

        return back()->with('success', 'Reply deleted successfully.');
    }
}


