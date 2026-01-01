<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community\ForumThread;
use App\Models\Community\ForumReply;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

/**
 * Handle content moderation and user bans.
 */
class AdminModerationController extends Controller
{
    public function index(): View
    {
        $flaggedThreadsQuery = ForumThread::where('status', 'flagged');
        $flaggedThreadsQuery->latest();
        $flaggedThreadsList = $flaggedThreadsQuery->get();

        $flaggedRepliesQuery = ForumReply::where('status', 'flagged');
        $flaggedRepliesQuery->with('thread');
        $flaggedRepliesQuery->latest();
        $flaggedRepliesList = $flaggedRepliesQuery->get();
        
        $bannedUsersQuery = User::whereNotNull('banned_at');
        $bannedUsersQuery->latest('banned_at');
        $bannedUsersList = $bannedUsersQuery->get();

        return view('admin.moderation.index', [
            'flaggedThreads' => $flaggedThreadsList,
            'flaggedReplies' => $flaggedRepliesList,
            'bannedUsers' => $bannedUsersList
        ]);
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

    public function banUser(User $user): RedirectResponse
    {
        $user->update(['banned_at' => now()]);
        return back()->with('success', "User {$user->name} has been banned.");
    }

    public function unbanUser(User $user): RedirectResponse
    {
        $user->update(['banned_at' => null]);
        return back()->with('success', "User {$user->name} has been unbanned.");
    }
}

