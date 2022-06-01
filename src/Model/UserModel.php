<?php

    namespace Models;
    require_once PROJECT_ROOT_PATH . 'Model/Database.php';

    use Auth\Exceptions\InvalidEmailException;
    use Auth\Exceptions\InvalidPasswordException;
    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\UserAlreadyExistException;
    use Auth\Exceptions\UserDoesNotExistException;
    use Auth\Exceptions\NotAuthorizedException;
    use Auth\Exceptions\AttributeDoesNotExistException;
    use Database\Exceptions\DatabaseError;

    use Managers\UserManager;
    use function password_hash;
    use const PASSWORD_BCRYPT;

    class UserModel extends Database
    {
        protected const TABLE = "user";

        protected function generateSafeFields(): array
        {
            return array(
                "user.id",
                "user.username",
                "user.first_name",
                "user.email",
                "user.surname",
                "user.date_joined",
                "user.last_login",
                "user.is_active",
                "user.profile_picture"
            );

        }

        protected function generateFields(): array
        {
            $array = json_decode(json_encode($this->generateSafeFields()));
            $array[] = "user.password";
            return $array;
        }


        /**
         * Get all users
         *
         * @param $limit int The number of users to get
         * @return array
         * @throws DatabaseError
         */

        public function getUsers(int $limit): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            user
                                        ORDER BY 
                                            id 
                                        LIMIT 
                                            ?",
                ["i", $limit]);
        }

        /**
         * Get a user by their ID
         * @param $id int The ID of the user to get
         * @return array The user details
         * @throws UserDoesNotExistException If the user doesn't exist
         * @throws DatabaseError
         */
        public function getUserById(int $id): array
        {
            $data = $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            user
                                        WHERE 
                                            id = ?",
                ["i", $id]);
            if ($data) {
                return $data[0];
            } else {
                throw new UserDoesNotExistException();
            }
        }


        /**
         * Get a user 
         * @return array The user details
         * @throws AttributeDoesNotExistException If an attribute doesn't exist
         * @throws DatabaseError
         * 
         */
        public function getUser(array $parameters): array
        {
            $query = "SELECT 
                            {$this->getSafeFields()}
                        FROM 
                            user
                        WHERE 
                            ";
            $arr=[""];
            foreach ($parameters as $key => $value) {
                if (in_array("user.".$key, $this->generateSafeFields())) {
                    $arr[]=$value;
                    if (strcmp($key,"id")==0) {
                        $arr[0].="i";
                    } else {
                        $arr[0].="s";
                    }
                    $query .= $key . " = ? AND ";
                } else {
                    throw new AttributeDoesNotExistException($message="Attribute $key Does Not Exist"); // TODO 
                }
            }
            $query = substr($query, 0, -5);
            $data = $this->select($query,$arr);
            if ($data) {
                return $data[0];
            } else {
                throw new UserDoesNotExistException();
            }
        }

        /**
         * @param string $username The username of the user to get
         * @return array The user's details
         * @throws UserDoesNotExistException If the user does not exist
         * @throws DatabaseError
         */
        public function getUserByUsername(string $username): array
        {

            $data = $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            user
                                        WHERE 
                                            username = ?",
                ["s", $username]);
            if ($data) {
                return $data[0];
            } else {
                throw new UserDoesNotExistException();
            }


        }

        /**
         * get a user by their email
         *
         * @param $userEmail string The email of the user to get
         * @return array The user
         * @throws UserDoesNotExistException If the user doesn't exist
         * @throws DatabaseError
         */
        public function getUserByEmail(string $userEmail): array
        {
            $data = $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            user 
                                        WHERE 
                                            email = ?",
                ["s", $userEmail]);
            if ($data) {
                return $data[0];
            } else {
                throw new UserDoesNotExistException();
            }

        }

        /**
         * Get a user by their username and password
         * @param $username string The username of the user to get
         * @return array The user
         * @throws UserDoesNotExistException
         * @throws DatabaseError
         */
        public function getUserByUsernameAndPassword(string $username): array
        {
            $data = $this->select("SELECT 
                                            {$this->getFields()}
                                        FROM 
                                            user 
                                        WHERE 
                                            username = ?",
                ["s", $username]);
            if ($data) {
                return $data[0];
            } else {
                throw new UserDoesNotExistException();
            }

        }

        /**
         * Verify that a user's email is unique and valid
         * @param $email string The email to check
         * @return bool True if the email is unique and valid
         * @throws UserAlreadyExistException If the email is already in use
         * @throws InvalidEmailException If the email is not valid
         * @throws DatabaseError
         */
        public function verifyEmail(string $email): bool
        {
            // count the number of users who do already have that specified email
            $occurrencesOfEmail = $this->select(
                'SELECT * FROM user WHERE email = ?',
                ["s", $email]);

            // if any user with that email does already exist
            if (count($occurrencesOfEmail) > 0) {
                // cancel the operation and report the violation of this requirement
                throw new UserAlreadyExistException("A user with the email $email already exists");
            }
            // check if the email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // cancel the operation and report the violation of this requirement
                throw new InvalidEmailException("The email $email is not valid");
            }
            // if no user with that email does already exist and the email is valid
            return true;
        }

        /**
         * Verify that a user's username is unique and valid
         * @param string $username The username to check
         * @return bool True if the username is unique and valid
         * @throws UserAlreadyExistException If the username is already in use
         * @throws DatabaseError
         */
        public function verifyUsername(string $username): bool
        {

            // count the number of users who do already have that specified username
            $occurrencesOfUsername = $this->select(
                'SELECT * FROM user WHERE username = ?',
                ["s", $username]);
            // if any user with that username does already exist
            if (count($occurrencesOfUsername) > 0) {
                // cancel the operation and report the violation of this requirement
                throw new UserAlreadyExistException("A user with the username $username already exists");
            }
            // if no user with that username does already exist
            return true;
        }

        /**
         * Validate a user's password
         * @param string $password The password to check
         * @return bool True if the password is valid
         * @throws InvalidPasswordException If the password is not valid
         */

        public function validatePassword(string $password): bool
        {
            // check if the password is valid
            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@\d@', $password);
            $specialChars = preg_match('@\W@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                // cancel the operation and report the violation of this requirement
                throw new InvalidPasswordException("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
            }
            // if the password is valid
            return true;
        }

        /**
         * create a new user
         *
         *
         * @param string $username The username of the user to create
         * @param string $password The password of the user to create
         * @param string|null $first_name
         * @param string|null $surname The surname of the user to create
         * @param string|null $email The email of the user to create
         * @param string|null $profilePicture The profile picture of the user to create
         * @return int the id of the new user
         * @throws DatabaseError
         * @throws InvalidEmailException
         * @throws InvalidPasswordException
         * @throws UserAlreadyExistException
         */
        public function createUser(string $username, string $password, string $first_name = null, string $surname = null,
                                   string $email = null, string $profilePicture = null): int
        {
            // check if the user already exists
            $this->verifyUsername($username);
            if ($email) {
                $this->verifyEmail($email);
            }
            // validate the password
            $this->validatePassword($password);
            // create the user
            return $this->insert("INSERT INTO user (username, password, first_name, surname, email, profile_picture) 
                                VALUES (?, ?, ?, ?, ?, ?)",
                ["ssssss", $username, password_hash($password, PASSWORD_BCRYPT), $first_name, $surname, $email, $profilePicture]);
        }

        /**
         * update a user
         *
         * @param int $userId The id of the user to update
         * @param string|null $username The username of the user to update
         * @param string|null $firstName The first_name of the user to update
         * @param string|null $surname The surname of the user to update
         * @param string|null $email The email of the user to update
         * @param string|null $profilePicture The profile picture of the user to update
         * @return int the id of the updated user
         * @throws InvalidEmailException If the email is not valid
         * @throws NotLoggedInException If the user is not logged in
         * @throws UserAlreadyExistException If the username is already in use
         * @throws UserDoesNotExistException If the user does not exist
         * @throws NotAuthorizedException If the user is not authorized to update the user
         * @throws DatabaseError
         */
        public function updateUser(int    $userId,
                                   string $username = null,
                                   string $firstName = null,
                                   string $surname = null,
                                   string $email = null,
                                   string $profilePicture = null): int
        {
            
            $fields = "";
            $params = [""];
            if ($username !== null && $this->verifyUsername($username)) {
                $fields .= "username = ?,";
                $params[] = $username;
                $params[0] = $params[0] . "s";

            }
            if ($firstName !== null) {
                $fields .= "first_name = ?,";
                $params[] = $firstName;
                $params[0] = $params[0] . "s";
            }
            if ($surname !== null) {
                $fields .= "surname = ?,";
                $params[] = $surname;
                $params[0] = $params[0] . "s";
            }
            if ($email !== null && $this->verifyEmail($email)) {
                $fields .= "email = ?,";
                $params[] = $email;
                $params[0] = $params[0] . "s";
            }
            if ($profilePicture !== null) {
                $fields .= "profile_picture = ?,";
                $params[] = $profilePicture;
                $params[0] = $params[0] . "s";
            }
            $fields = substr($fields, 0, -1);
            $params[0] = $params[0] . "i";
            $params[] = $userId;
            $rowsUpdated = $this->update("UPDATE user SET " . $fields . " WHERE id = ?", $params);
            if ($rowsUpdated == 0) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $userId;
        }

        /**
         * Update the password of a user
         * @param int $userId The id of the user to update
         * @param string $password The new password of the user to update
         *
         * @throws NotLoggedInException If the user is not logged in
         * @throws InvalidPasswordException If the password is not valid
         * @throws UserDoesNotExistException If the user does not exist
         * @throws NotAuthorizedException If the user is not authorized to update the user
         * @throws DatabaseError
         */
        public function updatePassword(int $userId, string $password): void
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }
            if ($this->validatePassword($password)) {
                $updatedRows = $this->update("UPDATE user SET password = ? WHERE id = ?", ["ss", password_hash($password,PASSWORD_BCRYPT), $userId]);
                if ($updatedRows == 0) {
                    throw new UserDoesNotExistException("User with id $userId does not exist");
                }

            }
        }


        /**
         * delete a user if the user is logged in
         *
         * @param int $userId The id of the user to delete
         * @return int the id of the deleted user
         * @throws UserDoesNotExistException If the user doesn't exist
         * @throws NotAuthorizedException If the user is not authorized to delete the user
         * @throws NotLoggedInException If the user is not logged in
         * @throws DatabaseError
         *
         */
        public function deleteUser(int $userId): int
        {
            if (True) {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }
            $rid=[];
            $chatroomm=new ChatroomModel();
            // try{
                $rid=$this->getChatRooms($userId);
            // }catch(UserDoesNotExistException $e){
            //     //do nothing
            // }
            foreach ($rid as $chatroom) {
                $chatroomm->deleteUserFromChatRoom($userId,$chatroom['id']);
            }}
            $mess=new MessageModel();
            $mess->deleteUserMessages($userId); 
            $pix=new PixelModel();
            $myPixels=$pix->getPixelsByUserId($userId);
            foreach ($myPixels as $pixel) {
                $pix->makeUserIdNull($pixel['id']);
            }
            
            $deleteRows = $this->delete("DELETE FROM user WHERE id = ?", ["i", $userId]);
            if ($deleteRows == 0) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $userId;
        }
    


        /**
         * Get the rooms of a user by their id if the user is logged in
         *
         *
         * @param int $userId
         * @return array The rooms of the user
         * @throws NotLoggedInException If the user is not logged in
         * @throws UserDoesNotExistException If the user doesn't exist
         * @throws NotAuthorizedException If the user is not allowed to view the rooms
         * @throws DatabaseError
         */
        public function getChatRooms(int $userId): array
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }
            $data = $this->select("
                                        SELECT 
                                            chat_room.id,
                                            chat_room.name
                                        FROM chat_room
                                        INNER JOIN chat_room_user
                                        ON chat_room.id = chat_room_user.chat_room_id
                                        WHERE chat_room_user.user_id = ?
                                        ORDER BY chat_room.id;", ["i", $userId]);
            if ($data == null) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $data;

        }


        /**
         * Get the messages of a user if the user is logged in and the room is in the user's rooms
         * and the room is not private else throw an exception
         *
         *
         * @param int $userId The id of the user
         * @return array The messages of the user
         * @throws NotLoggedInException If the user is not logged in
         * @throws NotAuthorizedException If the user is not authorized to view the messages
         * @throws UserDoesNotExistException If the user doesn't exist
         * @throws DatabaseError
         */
        public function getMessages(int $userId): array
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }

            $messageModel = new MessageModel();

            $data = $this->select("
                                        SELECT 
                                            {$messageModel->getSafeFields()}
                                        FROM message
                                        INNER JOIN user
                                        ON message.user_id = user.id
                                        WHERE message.chat_room_id = ?
                                        ORDER BY message.sent_date", ["i", $userId]);
            if ($data == null) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $data;
        }

        /**
         * search of the username, first name, email and surname of the user
         *
         * @param string $search
         * @param int $limit
         * @return array
         * @throws DatabaseError
         */
        public function searchUser(string $search, int $limit = 10): array
        {
            $data = $this->select("
                                        SELECT 
                                            {$this->getSafeFields()}
                                        FROM user
                                        WHERE user.username LIKE ?
                                        OR user.first_name LIKE ?
                                        OR user.surname LIKE ?
                                        OR user.email LIKE ?
                                        ORDER BY user.id
                                        LIMIT ?",
                ["ssssi", "%" . $search . "%", "%" . $search . "%", "%" . $search . "%", "%" . $search . "%", $limit]);

            if ($data == null) {
                return [];
            }

            return $data;
        }

        public function getMyChatrooms(int $userId): array
        {
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }
            $data = $this->select("
                                        SELECT 
                                            chat_room.id,
                                            chat_room.owner_id
                                        FROM chat_room
                                        WHERE chat_room.owner_id = ?
                                        ORDER BY chat_room.id;", ["i", $userId]);
            if ($data == null) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $data;
        }



    }