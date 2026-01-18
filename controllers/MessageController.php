<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Models\Message;
use Models\Listing;
use Models\Conversation;

class MessageController
{
    public function index(): void
    {
        Auth::requireAuth();
        $userId = Auth::id();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $tab = $_GET['tab'] ?? 'received';

        if ($tab === 'received') {
            // BLUE category: Conversations about user's listings
            $conversations = Conversation::getReceivedByUser($userId, $page, 10);
            $totalCount = Conversation::countReceivedByUser($userId);
            $unreadCount = Conversation::getUnreadCount($userId, 'received');
        } else {
            // ORANGE category: Conversations user initiated
            $conversations = Conversation::getSentByUser($userId, $page, 10);
            $totalCount = Conversation::countSentByUser($userId);
            $unreadCount = Conversation::getUnreadCount($userId, 'sent');
        }

        $totalPages = ceil($totalCount / 10);

        $this->view('messages/index', [
            'title' => __('messages.title'),
            'conversations' => $conversations,
            'tab' => $tab,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'unreadCount' => $unreadCount,
            'unreadReceived' => Conversation::getUnreadCount($userId, 'received'),
            'unreadSent' => Conversation::getUnreadCount($userId, 'sent')
        ]);
    }

    public function send(int $listingId): void
    {
        $listing = Listing::find($listingId);

        if (!$listing) {
            Flash::error(__('listing.not_found'));
            header('Location: /listings');
            exit;
        }

        // If user is authenticated, sender_email comes from their account
        $rules = [
            'message' => 'required|min:10'
        ];

        if (!Auth::check()) {
            $rules['sender_email'] = 'required|email';
        }

        $validator = new Validator($_POST, $rules);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /listings/{$listingId}");
            exit;
        }

        // Track sender_user_id if user is authenticated
        $senderUserId = null;
        $senderEmail = $_POST['sender_email'] ?? null;
        $senderName = $_POST['sender_name'] ?? null;

        if (Auth::check()) {
            $senderUserId = Auth::id();
            $user = \Models\User::find($senderUserId);
            $senderEmail = $user['email'];
            $senderName = $user['full_name'];
        }

        // Find or create conversation
        $conversationId = Conversation::findOrCreate(
            $listingId,
            $listing['user_id'], // user1 = listing owner
            $senderUserId,       // user2 = sender (can be null for anonymous)
            $senderEmail,
            $senderName
        );

        // Send message in conversation
        Message::sendInConversation(
            $conversationId,
            $_POST['message'],
            $senderUserId ?? 0,
            'user2', // Sender is always user2 for initial inquiry
            $senderEmail,
            $senderName
        );

        Flash::success(__('messages.sent_success'));
        header("Location: /listings/{$listingId}");
        exit;
    }

    public function viewConversation(int $id): void
    {
        Auth::requireAuth();
        $userId = Auth::id();

        $conversation = Conversation::getById($id);

        if (!$conversation) {
            Flash::error(__('messages.conversation_not_found'));
            header('Location: /messages');
            exit;
        }

        // Security: Check if user is part of this conversation
        if ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId) {
            Flash::error(__('errors.access_denied'));
            header('Location: /messages');
            exit;
        }

        // Get all messages in conversation
        $messages = Message::getByConversation($id);

        // Mark as read
        Conversation::markAsRead($id, $userId);

        // Determine which user type the current user is
        $userType = ($conversation['user1_id'] == $userId) ? 'user1' : 'user2';

        $this->view('messages/conversation', [
            'title' => __('messages.conversation_title'),
            'conversation' => $conversation,
            'messages' => $messages,
            'userType' => $userType
        ]);
    }

    public function reply(int $conversationId): void
    {
        Auth::requireAuth();
        $userId = Auth::id();

        $conversation = Conversation::getById($conversationId);

        if (!$conversation) {
            Flash::error(__('messages.conversation_not_found'));
            header('Location: /messages');
            exit;
        }

        // Security check
        if ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId) {
            Flash::error(__('errors.access_denied'));
            header('Location: /messages');
            exit;
        }

        $validator = new Validator($_POST, [
            'message' => 'required|min:1'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /messages/conversation/{$conversationId}");
            exit;
        }

        // Determine sender type and get sender info
        $senderType = ($conversation['user1_id'] == $userId) ? 'user1' : 'user2';

        // Get sender email and name from conversation
        if ($senderType === 'user1') {
            $senderEmail = $conversation['user1_email'];
            $senderName = $conversation['user1_name'];
        } else {
            $senderEmail = $conversation['user2_email'];
            $senderName = $conversation['user2_name'];
        }

        // Send reply
        Message::sendInConversation(
            $conversationId,
            $_POST['message'],
            $userId,
            $senderType,
            $senderEmail,
            $senderName
        );

        Flash::success(__('messages.reply_sent'));
        header("Location: /messages/conversation/{$conversationId}");
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
