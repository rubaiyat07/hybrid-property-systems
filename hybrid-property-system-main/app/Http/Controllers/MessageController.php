<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        try {
            $userId = auth()->id();
            $messages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->with(['sender:id,name', 'receiver:id,name'])
            ->latest()
            ->limit(20)
            ->get();

            $conversations = $messages->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            })->map(function ($group, $otherUserId) use ($userId) {
                $latest = $group->last();
                $otherUser = $latest->sender_id == $userId ? $latest->receiver : $latest->sender;
                return [
                    'user_id' => $otherUserId,
                    'user_name' => $otherUser->name,
                    'last_message' => $latest->content,
                    'unread' => $group->where('receiver_id', $userId)->whereNull('read_at')->count(),
                    'updated_at' => $latest->created_at,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            Log::error('Messages index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load messages'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        try {
            // Handle both JSON and form data
            $data = $request->all();
            if ($request->isJson()) {
                $data = $request->json()->all();
            }

            $validator = Validator::make($data, [
                'receiver_id' => 'required|integer|exists:users,id',
                'content' => 'required|string|max:1000|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $receiverId = (int) $data['receiver_id'];

            if ($receiverId === auth()->id()) {
                return response()->json(['success' => false, 'error' => 'Cannot message yourself'], 400);
            }

            $targetUser = User::find($receiverId);
            if (!$targetUser) {
                return response()->json(['success' => false, 'error' => 'User not found'], 404);
            }

            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $receiverId,
                'content' => $data['content']
            ]);

            $message->load(['sender:id,name', 'receiver:id,name']);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Message creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function conversation($userIdParam)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $userId = (int) $userIdParam;

        if ($userId <= 0) {
            return response()->json(['success' => false, 'error' => 'Invalid user ID'], 400);
        }

        if ($userId === auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Cannot message yourself'], 400);
        }

        $targetUser = User::find($userId);
        if (!$targetUser) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }

        try {
            $messages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $userId);
            })->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', auth()->id());
            })
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

            // Mark messages as read
            Message::where('sender_id', $userId)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Conversation fetch error for user ' . $userId . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load conversation'
            ], 500);
        }
    }
}
