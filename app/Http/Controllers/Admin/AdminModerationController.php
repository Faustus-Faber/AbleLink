<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community\ForumThread;
use App\Models\Community\ForumReply;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminModerationController extends Controller
{
    public function index(): View
    {
        $flaggedThreads = ForumThread::where('status', 'flagged')->latest()->get();
        // Since replies are separate, we might want to fetch them too.
        // Assuming ForumReply model has a status column as well based on previous context.
        $flaggedReplies = ForumReply::where('status', 'flagged')->with('thread')->latest()->get();
        
        // Fetch banned users for management list
        $bannedUsers = \App\Models\Auth\User::whereNotNull('banned_at')->latest('banned_at')->get();

        return view('admin.moderation.index', compact('flaggedThreads', 'flaggedReplies', 'bannedUsers'));
    }

    public function approveThread(ForumThread $thread): RedirectResponse
    {
        $thread->update(['status' => 'active']);
        return back()->with('success', 'Thread approved and published.');
    }

    public function deleteThread(ForumThread $thread): RedirectResponse
    {
        $thread->delete();
        return back()->with('success', 'Thread deleted.');
    }

    public function approveReply(ForumReply $reply): RedirectResponse
    {
        $reply->update(['status' => 'active']);
        return back()->with('success', 'Reply approved and published.');
    }

    public function deleteReply(ForumReply $reply): RedirectResponse
    {
        $reply->delete();
        return back()->with('success', 'Reply deleted.');
    }

    public function banUser(\App\Models\Auth\User $user): RedirectResponse
    {
        $user->update(['banned_at' => now()]);
        return back()->with('success', "User {$user->name} has been banned.");
    }

    public function unbanUser(\App\Models\Auth\User $user): RedirectResponse
    {
        $user->update(['banned_at' => null]);
        return back()->with('success', "User {$user->name} has been unbanned.");
    }
}

