<?php

    namespace Models;
    require_once PROJECT_ROOT_PATH . 'Model/Database.php';

    use Auth\Exceptions\NotAuthorizedException;
    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\PermissionDoesNotExistException;
    use Auth\Exceptions\PermissionAlreadyExistException;
    use Auth\Exceptions\UserDoesNotExistException;
    use Database\Exceptions\DatabaseError;

    // use Managers\UserManager;
    use Managers\UserManager;
    use function password_hash;
    use const PASSWORD_BCRYPT;

    class PermissionModel extends Database
    {
        protected const TABLE = "permissions";

        protected function generateSafeFields(): array
        {
            return [
                "permission.id",
                "permission.name",
                "permission.description",
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }


        /**
         * Get all permissions
         *
         * @param $limit int The number of permissions to get
         * @return array
         * @throws DatabaseError
         */

        public function getPermissions(int $limit): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            permission
                                        ORDER BY 
                                            id 
                                        LIMIT 
                                            ?",
                ["i", $limit]);
        }

        /**
         * Get a permission by their ID
         * @param $id int The ID of the permission to get
         * @return array The permission details
         * @throws PermissionDoesNotExistException If the permission doesn't exist
         * @throws DatabaseError
         */
        public function getPermissionById(int $id): array
        {
            $data = $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            permission
                                        WHERE 
                                            id = ?",
                ["i", $id]);
            if ($data) {
                return $data[0];
            } else {
                throw new PermissionDoesNotExistException();
            }
        }


        /**
         * Verify that a permission's name is unique and valid
         * @param $name string The name to check
         * @return bool True if the name is unique and valid
         * @throws DatabaseError
         */
        public function verifyName(string $name): bool
        {
            //verify if the name is empty
            if ($name == "") {
                return false;
            }
            // count the number of users who do already have that specified name
            $occurrencesOfName = $this->select(
                'SELECT * FROM permission WHERE name = ?',
                ["s", $name]);
            if (count($occurrencesOfName) > 0) {
                return false;
            }
            return true;
        }

        /**
         * create a new permission
         *
         *
         * @param string $name The name of the permission to create
         * @param string $description The description of the permission to create
         * @return int the id of the new permission
         * @throws DatabaseError
         * @throws PermissionAlreadyExistException
         */
        public function createPermission(string $name, string $description): int
        {
            // check if the permission already exists
            if (!$this->verifyName($name)) {
                throw new PermissionAlreadyExistException();
            }
            // create the permission
            return $this->insert("INSERT INTO permission (name, description) 
                                VALUES (?, ?)",
                ["ss", $name, $description]);
        }

        /**
         * update a permission
         *
         * @param int $permissionId The id of the permission to update
         * @param string|null $name The name of the permission to update
         * @param string|null $description The description of the permission to update
         * @return int the id of the updated permission
         * @throws InvalidNameException If the name is not valid
         * @throws PermissionAlreadyExistException If the username is already in use
         * @throws PermissionDoesNotExistException If the user does not exist
         * @throws DatabaseError
         * @throws UserDoesNotExistException
         */
        public function updatePermission(int    $permissionId,
                                         string $name = null,
                                         string $description = null): int
        {
            // check if the permission already exists
            if (!$this->verifyName($name)) {
                throw new PermissionAlreadyExistException();
            }


            $fields = substr($fields, 0, -1);
            $params[0] = $params[0] . "i";
            $params[] = $userId;
            $rowsUpdated = $this->update("UPDATE permission SET " . $name . " WHERE id = ?", $params);
            if ($rowsUpdated == 0) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $userId;
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
            $userManager = new UserManager();
            if ($userManager->getLoggedInUserId() != $userId) {
                throw new NotAuthorizedException();
            }

            $deleteRows = $this->delete("DELETE FROM user WHERE id = ?", ["i", $userId]);
            if ($deleteRows == 0) {
                throw new UserDoesNotExistException("User with id $userId does not exist");
            }
            return $userId;
        }


    }