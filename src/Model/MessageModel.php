<?php

    namespace Models;
    require_once PROJECT_ROOT_PATH . 'Model/Database.php';

    use Auth\Exceptions\NotAuthorizedException;
    use Database\Exceptions\DatabaseError;
    use DateTime;
    use Exception;
    use Managers\UserManager;

    class MessageModel extends Database
    {
        const  TABLE = "message";

        protected function generateSafeFields(): array
        {
            return [
                "message.id",
                "message.user_id",
                "message.chat_room_id",
                "message.content",
                "message.sent_date"
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }


        /**
         * Get all messages
         *
         * @param $limit int The number of messages to get
         * @return array
         * @throws Exception
         */
        public function getMessages(int $limit): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            message
                                        ORDER BY 
                                            sent_date
                                        LIMIT 
                                            ?",
                ["i", $limit]);
        }

        /**
         * Get a message by their ID
         * @param $id int The ID of the message to get
         * @return array The message details
         * @throws Exception If the message doesn't exist
         */
        public function getMessageById(int $id): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            message 
                                        WHERE 
                                            id = ?",
                ["i", $id]);
        }

        /**
         * Full text search for message content
         * @param $query string The query to search for
         * @param $limit int The number of messages to get
         * @return array The messages
         * @throws Exception If the query is empty
         */
        public function searchMessages(string $query, int $limit): array
        {
            if (empty($query)) {
                throw new Exception("Query cannot be empty");
            }

            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            message 
                                        WHERE 
                                            MATCH(content) AGAINST(?)
                                        ORDER BY 
                                            sent_date
                                        LIMIT 
                                            ?",
                ["si", $query, $limit]);
        }

        /**
         * Get messages in a datetime range
         * @param $startDate DateTime The start date
         * @param $endDate DateTime The end date
         * @param $limit int The number of messages to get
         * @return array The messages
         * @throws Exception If the start date is after the end date
         *
         **/
        public function getMessagesInDateRange(DateTime $startDate, DateTime $endDate, int $limit): array
        {
            if ($startDate > $endDate) {
                throw new Exception("Start date cannot be after end date");
            }

            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            message 
                                        WHERE 
                                            sent_date >= ?
                                        AND 
                                            sent_date <= ?
                                        ORDER BY 
                                            sent_date
                                        LIMIT 
                                            ?",
                ["ssi", $startDate->format("Y-m-d H:i:s"), $endDate->format("Y-m-d H:i:s"), $limit]);
        }

        /**
         * Create a new message in the database with the current datetime
         *
         * @param $content string The content of the message
         * @param $userId int The ID of the user who sent the message
         * @param $chatRoomId int The ID of the chat room the message was sent in
         *
         * @throws Exception
         */
        public function createMessage(string $content, int $userId, int $chatRoomId): int
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }
            return $this->insert("INSERT INTO message (content, user_id, chat_room_id, sent_date) VALUES (?, ?, ?, ?)",
                ["s", $content, "i", $userId, "i", $chatRoomId, "d", date("Y-m-d H:i:s")]);
        }


        /**
         * modify a message
         *
         * @param int $msgId The id of the message to modify
         * @param string $content The modified content
         * @return int the id of the updated message
         * @throws Exception If the message doesn't exist
         */
        public function modifyMessage(int $msgId, string $content = ""): int
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != self::getMessageById($msgId)["user_id"]) {
                throw new NotAuthorizedException();
            }
            return $this->update("UPDATE message SET content = ? WHERE id = ?",
                ["si", $content, $msgId]);
        }

        /**
         * delete a message
         *
         * @param int $msgId The id of the message to delete
         * @return int the id of the deleted user
         * @throws Exception If the message doesn't exist
         */
        public function deleteMessage(int $msgId): int
        {
            return $this->delete("DELETE FROM message WHERE id = ?", ["i", $msgId]);
        }


    }