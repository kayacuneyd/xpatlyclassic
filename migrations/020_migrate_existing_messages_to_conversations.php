<?php

/**
 * Migration: Migrate existing messages to conversation structure
 *
 * Creates a conversation for each existing message and links them together.
 * This ensures backward compatibility with existing data.
 */

return [
    'up' => function($conn) {
        // Get all existing messages
        $stmt = $conn->query("
            SELECT m.*, l.user_id as listing_owner_id
            FROM messages m
            INNER JOIN listings l ON m.listing_id = l.id
            WHERE m.conversation_id IS NULL
            ORDER BY m.created_at ASC
        ");

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messages as $message) {
            // Check if a conversation already exists for this combination
            $lookupStmt = $conn->prepare("
                SELECT id FROM conversations
                WHERE listing_id = ?
                AND user1_id = ?
                AND (
                    (user2_id = ? AND user2_id IS NOT NULL)
                    OR (user2_email = ? AND user2_id IS NULL)
                )
                LIMIT 1
            ");

            $lookupStmt->execute([
                $message['listing_id'],
                $message['listing_owner_id'],
                $message['sender_user_id'],
                $message['sender_email']
            ]);

            $existingConv = $lookupStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingConv) {
                // Use existing conversation
                $conversationId = $existingConv['id'];

                // Update last_message_at
                $updateStmt = $conn->prepare("
                    UPDATE conversations
                    SET last_message_at = ?,
                        user1_unread_count = user1_unread_count + 1,
                        updated_at = ?
                    WHERE id = ?
                ");
                $updateStmt->execute([
                    $message['created_at'],
                    $message['created_at'],
                    $conversationId
                ]);
            } else {
                // Create new conversation
                $createStmt = $conn->prepare("
                    INSERT INTO conversations (
                        listing_id,
                        user1_id,
                        user2_id,
                        user2_email,
                        user2_name,
                        last_message_at,
                        user1_unread_count,
                        user2_unread_count,
                        created_at,
                        updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $createStmt->execute([
                    $message['listing_id'],
                    $message['listing_owner_id'],
                    $message['sender_user_id'],
                    $message['sender_email'],
                    $message['sender_name'],
                    $message['created_at'],
                    $message['read_at'] ? 0 : 1, // unread count for owner
                    0, // sender has read their own message
                    $message['created_at'],
                    $message['created_at']
                ]);

                $conversationId = $conn->lastInsertId();
            }

            // Link message to conversation
            $linkStmt = $conn->prepare("
                UPDATE messages
                SET conversation_id = ?,
                    sender_type = 'user2'
                WHERE id = ?
            ");
            $linkStmt->execute([$conversationId, $message['id']]);
        }

        echo "Migrated " . count($messages) . " messages to conversations.\n";
    },

    'down' => function($conn) {
        // Clear conversation links from messages
        $conn->exec("UPDATE messages SET conversation_id = NULL, sender_type = 'user2'");

        // Delete all conversations
        $conn->exec("DELETE FROM conversations");

        echo "Rolled back conversation migration.\n";
    }
];
