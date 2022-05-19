<?php

    namespace Models;

    use Exception;

    class ChatRoomModel extends Database
    {
        const TABLE = "chat_room";


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

        /**
         * Get all chat rooms
         *
         * @param $limit int The number of chat rooms to get
         * @return array
         * @throws Exception
         */
        public function getChatRooms(int $limit): array
        {
            return $this->select("SELECT 
                                            *
                                        FROM 
                                            chat_room 
                                        ORDER BY 
                                            id 
                                        LIMIT 
                                            ?",
                ["i", $limit]);
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
        public function getMessages(int $chatRoomId, int $limit = 10): array
        {
            return $this->select("SELECT * 
                                        FROM message 
                                        WHERE chat_room_id = ? 
                                        ORDER BY sent_date DESC 
                                        LIMIT ?
                                        ", ["ii", $chatRoomId, $limit]);
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
        public function createChatRoom(string $chatRoomName, int $ownerId): array
        {
            return $this->select("INSERT INTO 
                                        chat_room (name, owner_id,created_at)
                                        VALUES (?, ?, NOW())
                                        ", ["si", $chatRoomName, $ownerId]);
        }


    }