<?php


    namespace Models;

    use Database\Exceptions\DatabaseError;
    use Auth\Exceptions\AttributeDoesNotExistException;
    use Auth\Exceptions\ElementDoesNotExistException;
    use mysqli;
    use mysqli_stmt;

    abstract class Database
    {
        protected ?mysqli $connection = null;
        protected const TABLE = "";
        protected array $FIELDS;
        protected array $FIELDS_SAFE;
        protected array $TYPES;


        /**
         * @throws DatabaseError
         */
        public function __construct()
        {
            try {
                $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

                if (mysqli_connect_errno()) {
                    throw new DatabaseError("Could not connect to database.");
                }
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
            $this->FIELDS = $this->generateFields();
            $this->FIELDS_SAFE = $this->generateSafeFields();
            $this->TYPES = $this->generateTypes();
        }

        abstract protected function generateSafeFields(): array;
        abstract protected function generateFields(): array;
        abstract protected function generateTypes(): array;


        /**
         * Get the safe fields seperated by commas
         *
         * @return string
         */
        public function getSafeFields(): string
        {
            return implode(", ", $this->FIELDS_SAFE);
        }

        /**
         * Get the fields seperated by commas
         *
         * @return string
         */
        protected function getFields(): string
        {
            return implode(", ", $this->FIELDS);
        }

        /**
         * Get the types of the fields
         *
         * @return string
         */
        protected function getTypes(): array
        {
            return $this->FIELDS;
        }



        /**
         * Selects rows from the database
         *
         *
         * @throws DatabaseError
         */
        public function select($query = "", $params = []): array
        {
            try {
                $stmt = $this->executeStatement($query, $params);
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                return $result;
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
        }


        /**
         * Inserts a new row into the database.
         *
         * @param string $query
         * @param array $params
         * @return int
         * @throws DatabaseError
         */
        public function insert(string $query = "", array $params = []): int
        {
            try {
                $stmt = $this->executeStatement($query, $params);
                $result = $stmt->insert_id;
                $stmt->close();

                return $result;
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
        }

        /**
         * Updates a row in the database.
         *
         * @param string $query
         * @param array $params
         * @return int
         * @throws DatabaseError
         */
        public function update(string $query = "", array $params = []): int
        {
            try {
                $stmt = $this->executeStatement($query, $params);
                $result = $stmt->affected_rows;
                $stmt->close();

                return $result;
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
        }

        /**
         * Deletes a row from the database.
         *
         * @param string $query
         * @param array $params
         * @return int
         * @throws DatabaseError
         */
        public function delete(string $query = "", array $params = []): int
        {
            try {
                $stmt = $this->executeStatement($query, $params);
                $result = $stmt->affected_rows;
                $stmt->close();

                return $result;
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
        }

        /**
         * @throws DatabaseError
         */
        protected function executeStatement($query = "", $params = []): mysqli_stmt
        {
            try {
                $stmt = $this->connection->prepare($query);

                if ($stmt === false) {
                    throw new DatabaseError("Unable to do prepared statement: " . $query);
                }

                if ($params) {
                    $stmt->bind_param($params[0], ...array_slice($params, 1));
                }

                $stmt->execute();

                return $stmt;
            } catch (DatabaseError $e) {
                throw new DatabaseError($e->getMessage());
            }
        }

        /**
         *
         *
         *
         * @throws DatabaseError
         */
        protected function getLastInsertId()
        {
            return $this->select("SELECT LAST_INSERT_ID()")[0]["LAST_INSERT_ID()"];
        }


        /**
         * Get child by anything you want
         * @return array The returned array
         * @throws AttributeDoesNotExistException If an attribute doesn't exist
         * @throws DatabaseError
         * 
         */
        public function getUser(array $parameters): array
        {
            $query = "SELECT {$this->getSafeFields()} FROM {$this->TABLE} WHERE ";
            $arr=[""];
            foreach ($parameters as $key => $value) {
                if (in_array("user.".$key, $this->generateSafeFields())) {
                    $arr[]=$value;
                    $arr[0].=$this->getTypes()[$key];
                    $query .= $key . " = ? AND ";
                } else {
                    throw new AttributeDoesNotExistException($message="Attribute $key Does Not Exist"); 
                }
            }
            $query = substr($query, 0, -5);
            $data = $this->select($query,$arr);
            if ($data) {
                return $data[0];
            } else {
                throw new ElementDoesNotExistException($message="Element Does Not Exist");
            }
        }

    }