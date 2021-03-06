<?php

namespace Models;

use Exception;

class ChatRoomModel extends Database
{

    protected function generateSafeFields(): array
    {
        return [
            "chat_room.id",
            "chat_room.name",
            "chat_room.description",
            "chat_room.is_private",
            "chat_room.created_at",
            "chat_room.owner_id",
        ];
    }

    protected function generateFields(): array
    {
        return $this->generateSafeFields();
    }

    protected function generateTypes(): array
    {
        return array(
            "chat_room.id" => "i",
            "chat_room.name" => "s",
            "chat_room.description" => "s",
            "chat_room.is_private" => "i",
            "chat_room.created_at" => "s",
            "chat_room.owner_id" => "i",
        );
    }

    protected function generateTable(): string
    {
        return "chat_room";
    }

    /**
     * Get all chat rooms
     *
     * @param $limit int The number of chat rooms to get
     * @return array
     * @throws Exception
     */
    public function getChatRooms(int $limit): array
    {
        return $this->select(
            "SELECT 
                                            *
                                        FROM 
                                            chat_room 
                                        ORDER BY 
                                            id 
                                        LIMIT 
                                            ?",
            ["i", $limit]
        );
    }

    /**
     * Get the users of a chat room
     *
     * @param int $chatRoomId The id of the chat room
     * @return array The messages of the chat room
     * @throws Exception If the chat room does not exist
     */
    public function getUsers(int $chatRoomId): array
    {
        $userModel = new UserModel();

        return $this->select("SELECT 
                                            {$userModel->getSafeFields()}
                                        FROM user
                                        INNER JOIN chat_room_user
                                        ON user.id = chat_room_user.user_id
                                        WHERE chat_room_user.chat_room_id = ?
                                        ORDER BY user.id
                                        ", ["i", $chatRoomId]);
    }

    /**
     * Get the messages of a chat room
     *
     * @param int $chatRoomId The id of the chat room
     * @param int $limit The number of messages to get
     * @return array The messages of the chat room
     * @throws Exception If the chat room does not exist
     */
    public function getMessages(int $chatRoomId, int $offset = 0, int $limit = 10): array
    {
        return $this->select("SELECT * 
                                        FROM message 
                                        WHERE chat_room_id = ? 
                                        ORDER BY sent_date DESC 
                                        LIMIT ?, ?
                                        ", ["iii", $chatRoomId, $offset, $limit]);
    }

    /**
     * Get the chat room with the given id
     *
     * @param int $chatRoomId The id of the chat room
     * @return array The chat room
     * @throws Exception If the chat room does not exist
     */
    public function getChatRoomById(int $chatRoomId): array
    {
        return $this->select("SELECT * 
                                        FROM chat_room
                                        WHERE id = ?
                                        ", ["i", $chatRoomId]);
    }

    /**
     * Get the chat rooms with the given name
     *
     * @param string $chatRoomName The name of the chat room
     * @return array The chat rooms
     * @throws Exception If the chat room does not exist
     */
    public function getChatRoomByName(string $chatRoomName): array
    {
        return $this->select("SELECT * 
                                        FROM chat_room 
                                        WHERE name = ?
                                        ", ["s", $chatRoomName]);
    }

    /**
     * Create a chat room
     *
     * @param string $chatRoomName The name of the chat room
     *
     * @return array The chat room
     * @throws Exception If the chat room does not exist
     */
    public function createChatRoom(string $chatRoomName, int $ownerId, string $description = null, int $isPrivate, string $picture = null): int
    {
        $a = $this->insert("INSERT INTO 
                                        chat_room (name, owner_id,created_at, description, is_private ,profile_picture)
                                        VALUES (?, ?, NOW(), ?, ?, ?)
                                        ", ["sisis", $chatRoomName, $ownerId, $description, $isPrivate, $picture]);
        $this->addUserToChatRoom($a, $ownerId);
        return $a;
    }

    public function deleteChatRoom(int $chatRoomId): bool
    {
        $messages = $this->getMessages($chatRoomId);
        $messageModel = new MessageModel();
        foreach ($messages as $message) {
            $messageModel->deleteMessage($message["id"]);
        }
        return $this->delete("DELETE FROM chat_room
                                        WHERE id = ?
                                        ", ["i", $chatRoomId]);
    }

    public function deleteUserFromChatRoom(int $userId, int $chatRoomId): bool
    {
        // get the owner of the chat room        
        $chatRoom = $this->getChatRoomById($chatRoomId);
        $this->delete("DELETE FROM chat_room_user
                                        WHERE user_id = ?
                                        AND chat_room_id = ?
                                        ", ["ii", $userId, $chatRoomId]);

        $ownerId = $chatRoom[0]["owner_id"];
        if ($ownerId == $userId) {
            if (count($this->getUsers($chatRoomId)) == 0) {
                $this->deleteChatRoom($chatRoomId);
            } else {
                $this->changeOwner($chatRoomId, $this->getUsers($chatRoomId)[0]["id"]);
            }
        }
        return true;
    }

    public function changeOwner(int $chatRoomId, int $newOwnerId): bool
    {
        return $this->update("UPDATE chat_room
                                        SET owner_id = ?
                                        WHERE id = ?
                                        ", ["ii", $newOwnerId, $chatRoomId]);
    }

    public function addUserToChatRoom(int $chatRoomId, int $userId): bool
    {
        $a = $this->select("SELECT user_id FROM chat_room_user
                                        WHERE chat_room_id = ?
                                        ", ["i", $chatRoomId]);
        if (!in_array($userId, $a)) {
            return $this->insert("INSERT INTO 
                                        chat_room_user (user_id, chat_room_id)
                                        VALUES (?, ?)
                                        ", ["ii", $userId, $chatRoomId]);
        }
    }

    public function searchPublicChatRoom(string $search, int $limit = 10): array
    {
        $data = $this->select(
            "
                                        SELECT 
                                            {$this->getSafeFields()}
                                        FROM chat_room
                                        WHERE chat_room.name LIKE ?
                                        OR chat_room.description LIKE ?
                                        AND chat_room.is_private = 0
                                        ORDER BY chat_room.name
                                        LIMIT ?",
            ["ssi", "%" . $search . "%", "%" . $search . "%", $limit]
        );

        if ($data == null) {
            return [];
        }

        return $data;
    }

    public function updateUsers(int $chatRoomId, array $users): bool
    {
        $a = $this->select("SELECT user_id FROM chat_room_user
                                        WHERE chat_room_id = ?
                                        ", ["i", $chatRoomId]);
        foreach ($users as $user) {
            if (!in_array($user, $a)) {
                $this->addUserToChatRoom($chatRoomId, $user);
            }
            return true;
        }
    }

    public function updateChatRoom(int $chatRoomId, string $chatRoomName = null, string $description = null, int $isPrivate = 0, string $picture = null): bool
    {

        $fields = "";
        $params = [""];
        if ($chatRoomName !== null) {
            $fields .= "chatRoomName = ?,";
            $params[] = $chatRoomName;
            $params[0] = $params[0] . "s";
        }
        if ($description !== null) {
            $fields .= "description = ?,";
            $params[] = $description;
            $params[0] = $params[0] . "s";
        }
        if ($isPrivate !== 0) {
            $fields .= "isPrivate = ?,";
            $params[] = $isPrivate;
            $params[0] = $params[0] . "s";
        }
        if ($picture !== null) {
            $fields .= "profile_picture = ?,";
            $params[] = $picture;
            $params[0] = $params[0] . "s";
        }
        $fields = substr($fields, 0, -1);
        $params[0] = $params[0] . "i";
        $params[] = $chatRoomId;
        $rowsUpdated = $this->update("UPDATE chatRoom SET " . $fields . " WHERE id = ?", $params);
        return $chatRoomId;
    }
}
