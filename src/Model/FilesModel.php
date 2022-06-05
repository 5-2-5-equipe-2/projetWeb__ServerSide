<?php

    namespace Models;

    use Database\Exceptions\DatabaseError;

    class FilesModel extends Database
    {

        protected function generateSafeFields(): array
        {
            return [
                "files.id",
                "files.url",
                "files.size",
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }

        protected function generateTypes(): array
        {
            return array(
                "files.id" => "i",
                "files.url" => "s",
                "files.size" => "i",
            );
        }

        protected function generateTable(): string
        {
            return "files";
        }

        /**
         * Create a new file
         * @param $name string The name of the color
         * @param $hexCode string The hex code of the color
         * @return int The ID of the color
         * @throws DatabaseError
         */
        public function postFile(string $name, string $hexCode,): int
        {
            $this->insert("INSERT INTO files (name, hex_code) VALUES (?, ?)",
            
                ["ss", $name, $hexCode]);
            
            return $this->getLastInsertId();
        }
    }