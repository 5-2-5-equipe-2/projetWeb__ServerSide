<?php

    namespace Managers;

    use Auth\Exceptions\InvalidEmailException;
    use Auth\Exceptions\InvalidPasswordException;
    use Auth\Exceptions\NotAuthorizedException;
    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\UserAlreadyExistException;
    use Auth\Exceptions\UserDoesNotExistException;
    use Auth\Exceptions\WrongCredentialsException;
    use Exception;
    use Models\UserModel;

    class UserManager
    {

        /** @var string session field for whether the client is currently signed in */
        const SESSION_FIELD_LOGGED_IN = 'auth_logged_in';
        /** @var string session field for the ID of the user who is currently signed in (if any) */
        const SESSION_FIELD_USER_ID = 'auth_user_id';
        /** @var string session field for the email address of the user who is currently signed in (if any) */
        const SESSION_FIELD_EMAIL = 'auth_email';
        /** @var string session field for the display name (if any) of the user who is currently signed in (if any) */
        const SESSION_FIELD_USERNAME = 'auth_username';
        /** @var string session field for the status of the user who is currently signed in (if any) as one of the constants from the {@see Status} class */
        const SESSION_FIELD_STATUS = 'auth_status';
        /** @var string session field for the UNIX timestamp in seconds of the session data's last resynchronization with its authoritative source in the database */
        const SESSION_FIELD_LAST_RESYNC = 'auth_last_resync';
        /**
         * @var UserModel
         */
        private $userModel;


        public function __construct()
        {
            $this->userModel = new UserModel();
        }


        /**
         * Create a new user
         * @param string $username The display name of the user
         * @param string $password The password of the user
         * @param string|null $email The email address of the user
         * @param callable|null $callback
         * @return int
         * @throws InvalidEmailException
         * @throws InvalidPasswordException
         * @throws UserAlreadyExistException
         */
        public function createUser(string $username, string $password, string $email = null, callable $callback = null): int
        {
            \ignore_user_abort(true);
            $verified = \is_callable($callback) ? 0 : 1;

            return $this->userModel->createUser($username, $password);
        }

        /**
         * Called when a user has successfully logged in
         *
         *
         * @param int $userId the ID of the user
         * @throws Exception
         */
        protected function onLoginSuccessful(int $userId)
        {
            session_unset();

            $userData = $this->userModel->getUserById($userId);
            // save the user data in the session variables maintained by this library
            $_SESSION[self::SESSION_FIELD_LOGGED_IN] = true;
            $_SESSION[self::SESSION_FIELD_USER_ID] = $userId;
            $_SESSION[self::SESSION_FIELD_EMAIL] = $userData['email'];
            $_SESSION[self::SESSION_FIELD_USERNAME] = $userData['username'];
            $_SESSION[self::SESSION_FIELD_LAST_RESYNC] = \time();
        }


        /**
         * Logs in the user with the given credentials
         *
         *
         * @param string $username
         * @param string $password
         * @throws UserDoesNotExistException
         * @throws WrongCredentialsException
         * @throws Exception
         */
        public function login(string $username, string $password)
        {
            $userData = $this->userModel->getUserByUsernameAndPassword($username);
            if (!\password_verify($password, $userData['password'])) {
                throw new WrongCredentialsException();
            }

            $this->onLoginSuccessful($userData['id']);
        }

        /**
         *  Logs out the user
         *
         *
         * @throws NotLoggedInException
         */
        public function logout(): void
        {
            if (isset($_SESSION[self::SESSION_FIELD_LOGGED_IN])) {
                unset($_SESSION[self::SESSION_FIELD_LOGGED_IN]);
                unset($_SESSION[self::SESSION_FIELD_USER_ID]);
                unset($_SESSION[self::SESSION_FIELD_EMAIL]);
                unset($_SESSION[self::SESSION_FIELD_USERNAME]);
                unset($_SESSION[self::SESSION_FIELD_LAST_RESYNC]);
            } else {
                throw new NotLoggedInException();
            }

        }


        /**
         * Updates the user's credentials
         *
         * @throws InvalidEmailException
         * @throws NotLoggedInException
         * @throws UserAlreadyExistException
         * @throws UserDoesNotExistException
         * @throws NotAuthorizedException
         */
        public function updateUser(...$args)
        {

            $this->userModel->updateUser(...$args);
        }

        /**
         * Check if the user is logged in
         *
         */
        public function isLoggedIn(): bool
        {
            return isset($_SESSION[self::SESSION_FIELD_LOGGED_IN]);
        }

        /**
         * Get the logged-in user's ID
         * @return int
         * @throws NotLoggedInException
         */
        public function getLoggedInUserId(): int
        {
            if ($this->isLoggedIn()) {
                return $_SESSION[self::SESSION_FIELD_USER_ID];
            } else {
                throw new NotLoggedInException("User is not logged in");
            }
        }
    }