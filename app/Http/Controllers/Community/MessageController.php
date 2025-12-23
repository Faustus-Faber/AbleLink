<?php

//F13 - Farhan Zarif
namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;

use App\Models\Community\Conversation;
use App\Models\Community\Message;
use App\Services\Ai\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $aiModerationService;

    public function __construct(AiModerationService $aiModerationService)
    {
        $this->aiModerationService = $aiModerationService;
    }

    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::where(function($q) use ($userId) {
                $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
            })
            ->whereHas('userOne')
            ->whereHas('userTwo')
            ->with(['userOne', 'userTwo', 'messages'])
            ->get();

        $users = \App\Models\Auth\User::where('id', '!=', $userId)->get();

        return view('messages.index', compact('conversations', 'users'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['messages', 'userOne', 'userTwo'])->findOrFail($id);
        
        // Authorization check
        if (Auth::id() !== $conversation->user_one_id && Auth::id() !== $conversation->user_two_id) {
            abort(403);
        }

        // Mark messages as read
        Message::where('conversation_id', $id)
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        $userId = Auth::id();
        $conversations = Conversation::where(function($q) use ($userId) {
                $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
            })
            ->whereHas('userOne')
            ->whereHas('userTwo')
            ->with(['userOne', 'userTwo'])
            ->get();
            
        $users = \App\Models\Auth\User::where('id', '!=', $userId)->get();

        return view('messages.show', compact('conversation', 'conversations', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'body' => 'nullable|required_without:attachment|string',
            'attachment' => 'nullable|file|max:25600',
        ]);

        // Safety Check for Body
        if ($request->body && !$this->aiModerationService->isSafe($request->body)) {
            return back()->with('error', 'Message blocked due to inappropriate content.');
        }

        $senderId = Auth::id();
        $recipientId = $request->recipient_id;

        // Find or create conversation
        $conversation = Conversation::where(function ($q) use ($senderId, $recipientId) {
            $q->where('user_one_id', $senderId)->where('user_two_id', $recipientId);
        })->orWhere(function ($q) use ($senderId, $recipientId) {
            $q->where('user_one_id', $recipientId)->where('user_two_id', $senderId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $senderId,
                'user_two_id' => $recipientId,
            ]);
        }

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'body' => $request->body ?? ($request->hasFile('attachment') ? 'Sent an attachment' : ''),
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('message_attachments', 'public');
            $messageData['attachment_path'] = $path;
            $messageData['attachment_type'] = $file->getClientMimeType();
            $messageData['attachment_original_name'] = $file->getClientOriginalName();
        }

        Message::create($messageData);

        return redirect()->route('messages.show', $conversation->id);
    }
    public function destroy($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where(function($q) {
                $q->where('user_one_id', Auth::id())
                  ->orWhere('user_two_id', Auth::id());
            })
            ->firstOrFail();

        $conversation->delete();

        return redirect()->route('messages.index')->with('success', 'Conversation deleted.');
    }
}



