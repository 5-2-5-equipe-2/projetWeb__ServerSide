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

        protected function generateTable(): string
        {
            return "color";
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
        public function isValidColor(string $name, string $hexCode)
        {
            //Check if hex code is valid
            if (!preg_match('/^#[a-f0-9]{6}$/i', $hexCode)) {
                throw new DatabaseError("Invalid hex code");
            }
            // check if color already exists
            $color = $this->get(array("name",$name));
            if (count($color) > 0) {
                throw new DatabaseError("Color already exists");
            }
            $color = $this->get(array("hex_code",$hexCode));
            if (count($color) > 0) {
                throw new DatabaseError("Color already exists");
            }
        }

    }