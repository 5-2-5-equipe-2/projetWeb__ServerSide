<?php

    namespace Models;

    use Database\Exceptions\DatabaseError;

    class ColorModel extends Database
    {

        protected function generateSafeFields(): array
        {
            return [
                "color.id",
                "color.name",
                "color.hex_code",
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }

        protected function generateTypes(): array
        {
            return array(
                "color.id" => "i",
                "color.name" => "s",
                "color.hex_code" => "s",
            );
        }

        /**
         * Get all colors
         *
         * @return array
         * @throws DatabaseError
         */
        public function getColors(): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            color
                                        ORDER BY 
                                            id");
        }

        /**
         * Get a color by their ID
         * @param $id int The ID of the color to get
         * @return array The color details
         * @throws DatabaseError
         */
        public function getColorById(int $id): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            color 
                                        WHERE 
                                            id = ?",
                ["i", $id]);
        }

        /**
         * Get a color by their name
         * @param $name string The name of the color to get
         * @return array The color details
         * @throws DatabaseError
         */
        public function getColorByName(string $name): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            color 
                                        WHERE 
                                            name = ?",
                ["s", $name]);
        }

        /**
         * Get a color by their hex code
         * @param $hexCode string The hex code of the color to get
         * @return array The color details
         * @throws DatabaseError
         */
        public function getColorByHexCode(string $hexCode): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            color 
                                        WHERE 
                                            hex_code = ?",
                ["s", $hexCode]);
        }

        /**
         * Create a new color
         * @param $name string The name of the color
         * @param $hexCode string The hex code of the color
         * @return int The ID of the color
         * @throws DatabaseError
         */
        public function createColor(string $name, string $hexCode): int
        {
            $this->isValidColor($name, $hexCode);

            $this->insert("INSERT INTO color (name, hex_code) VALUES (?, ?)",
                ["ss", $name, $hexCode]);
            return $this->getLastInsertId();
        }

        /**
         * Update a color
         * @param $id int The ID of the color to update
         * @param $name string The name of the color
         * @param $hexCode string The hex code of the color
         * @return int The ID of the color
         * @throws DatabaseError
         */
        public function updateColor(int $id, string $name, string $hexCode): int{
            $this->isValidColor($name, $hexCode);
            $this->update("UPDATE color SET name = ?, hex_code = ? WHERE id = ?",
                ["ssi", $name, $hexCode, $id]);
            return $id;
        }

        /**
         * @throws DatabaseError
         */
        private function isValidColor(string $name, string $hexCode)
        {
            //Check if hex code is valid
            if (!preg_match('/^#[a-f0-9]{6}$/i', $hexCode)) {
                throw new DatabaseError("Invalid hex code");
            }
            // check if color already exists
            $color = $this->getColorByName($name);
            if (count($color) > 0) {
                throw new DatabaseError("Color already exists");
            }
            $color = $this->getColorByHexCode($hexCode);
            if (count($color) > 0) {
                throw new DatabaseError("Color already exists");
            }
        }

    }