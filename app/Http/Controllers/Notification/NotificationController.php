<?php
// F9 - Evan Yuvraj Munshi

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        
        // Mark all as read when visiting the full index? Or just list them?
        // Usually index page marks them as read or lets user do it. 
        // For simple UX, let's keep them unread until clicked or explicitly marked.
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back(); // Or json response if ajax
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
