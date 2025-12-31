<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Models\Message;
use Models\Listing;

class MessageController
{
    public function index(): void
    {
        Auth::requireAuth();
        $userId = Auth::id();

        // BLUE category: Messages received about user's listings
        $receivedMessages = Message::getReceivedByUser($userId);

        // ORANGE category: Messages user sent to other listings
        $sentMessages = Message::getSentByUser($userId);

        // Unread counts
        $unreadReceived = Message::getUnreadCount($userId);
        $unreadSent = Message::getUnreadSentCount($userId);

        $this->view('messages/index', [
            'title' => __('messages.title'),
            'receivedMessages' => $receivedMessages,
            'sentMessages' => $sentMessages,
            'unreadReceived' => $unreadReceived,
            'unreadSent' => $unreadSent
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

        $validator = new Validator($_POST, [
            'sender_email' => 'required|email',
            'message' => 'required|min:10'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /listings/{$listingId}");
            exit;
        }

        // Track sender_user_id if user is authenticated
        $senderUserId = null;
        if (Auth::check()) {
            $senderUserId = Auth::id();
        }

        Message::send(
            $listingId,
            $_POST['sender_email'],
            $_POST['message'],
            $_POST['sender_name'] ?? null,
            $senderUserId
        );

        // TODO: Send email notification to listing owner

        Flash::success(__('messages.sent_success'));
        header("Location: /listings/{$listingId}");
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
