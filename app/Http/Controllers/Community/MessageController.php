<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\Conversation;
use App\Models\Community\Message;
use App\Models\Auth\User;
use App\Services\Ai\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class MessageController extends Controller
{
    private AiModerationService $aiModerationService;

    public function __construct(AiModerationService $aiModerationService)
    {
        $this->aiModerationService = $aiModerationService;
    }

    public function index(): View
    {
        $currentUserId = Auth::id();
        
        $conversationQuery = Conversation::where(function(Builder $query) use ($currentUserId) {
            $userOneConstraint = $query->where('user_one_id', $currentUserId);
            $userOneConstraint->orWhere('user_two_id', $currentUserId);
        });
        
        $conversationQuery->whereHas('userOne');
        $conversationQuery->whereHas('userTwo');
        $conversationQuery->with(['userOne', 'userTwo', 'messages']);
        
        $allConversationsList = $conversationQuery->get();

        $userQuery = User::where('id', '!=', $currentUserId);
        $otherUsersList = $userQuery->get();

        return view('messages.index', [
            'conversations' => $allConversationsList,
            'users' => $otherUsersList
        ]);
    }

    public function show(string $conversationId): View
    {
        $conversationQuery = Conversation::with(['messages', 'userOne', 'userTwo']);
        $targetConversation = $conversationQuery->findOrFail($conversationId);
        
        $currentUserId = Auth::id();
        $isUserOne = $currentUserId === $targetConversation->user_one_id;
        $isUserTwo = $currentUserId === $targetConversation->user_two_id;
        
        if ($isUserOne === false) {
            if ($isUserTwo === false) {
                 abort(403);
            }
        }

        $unreadMessagesQuery = Message::where('conversation_id', $conversationId);
        $unreadMessagesQuery->where('sender_id', '!=', $currentUserId);
        $unreadMessagesQuery->update(['is_read' => true]);

        $sidebarConversationQuery = Conversation::where(function(Builder $query) use ($currentUserId) {
            $userOneConstraint = $query->where('user_one_id', $currentUserId);
            $userOneConstraint->orWhere('user_two_id', $currentUserId);
        });
        
        $sidebarConversationQuery->whereHas('userOne');
        $sidebarConversationQuery->whereHas('userTwo');
        $sidebarConversationQuery->with(['userOne', 'userTwo']);
        
        $sidebarConversationsList = $sidebarConversationQuery->get();
            
        $userQuery = User::where('id', '!=', $currentUserId);
        $otherUsersList = $userQuery->get();

        return view('messages.show', [
            'conversation' => $targetConversation,
            'conversations' => $sidebarConversationsList,
            'users' => $otherUsersList
        ]);
    }

    public function store(Request $incomingRequest): RedirectResponse
    {
        $incomingRequest->validate([
            'recipient_id' => 'required|exists:users,id',
            'body' => 'nullable|required_without:attachment|string',
            'attachment' => 'nullable|file|max:25600',
        ]);

        $messageBody = $incomingRequest->body;
        
        if ($messageBody !== null) {
            $isSafeContent = $this->aiModerationService->isSafe($messageBody);
            if ($isSafeContent === false) {
                return back()->with('error', 'Message blocked due to inappropriate content.');
            }
        }

        $senderId = Auth::id();
        $recipientId = $incomingRequest->recipient_id;

        $existingConversationQuery = Conversation::where(function (Builder $query) use ($senderId, $recipientId) {
            $constraint = $query->where('user_one_id', $senderId);
            $constraint->where('user_two_id', $recipientId);
        });
        
        $existingConversationQuery->orWhere(function (Builder $query) use ($senderId, $recipientId) {
            $constraint = $query->where('user_one_id', $recipientId);
            $constraint->where('user_two_id', $senderId);
        });
        
        $targetConversation = $existingConversationQuery->first();

        if ($targetConversation === null) {
            $targetConversation = Conversation::create([
                'user_one_id' => $senderId,
                'user_two_id' => $recipientId,
            ]);
        }

        $finalBody = $messageBody;
        if ($finalBody === null) {
            $hasAttachment = $incomingRequest->hasFile('attachment');
            if ($hasAttachment === true) {
                $finalBody = 'Sent an attachment';
            } else {
                $finalBody = '';
            }
        }

        $newMessageData = [];
        $newMessageData['conversation_id'] = $targetConversation->id;
        $newMessageData['sender_id'] = $senderId;
        $newMessageData['body'] = $finalBody;

        if ($incomingRequest->hasFile('attachment')) {
            $uploadedFile = $incomingRequest->file('attachment');
            $storagePath = $uploadedFile->store('message_attachments', 'public');
            
            $newMessageData['attachment_path'] = $storagePath;
            $newMessageData['attachment_type'] = $uploadedFile->getClientMimeType();
            $newMessageData['attachment_original_name'] = $uploadedFile->getClientOriginalName();
        }

        Message::create($newMessageData);

        return redirect()->route('messages.show', $targetConversation->id);
    }

    public function destroy(string $conversationId): RedirectResponse
    {
        $currentUserId = Auth::id();
        $conversationQuery = Conversation::where('id', $conversationId);
        
        $conversationQuery->where(function(Builder $query) use ($currentUserId) {
            $userOneConstraint = $query->where('user_one_id', $currentUserId);
            $userOneConstraint->orWhere('user_two_id', $currentUserId);
        });
        
        $targetConversation = $conversationQuery->firstOrFail();
        
        $targetConversation->messages()->delete();
        $targetConversation->delete();

        return redirect()->route('messages.index')->with('success', 'Conversation deleted.');
    }
}



