<?php

    namespace Models;
    require_once PROJECT_ROOT_PATH . 'Model/Database.php';

    use Auth\Exceptions\PixelAlreadyExistException;
    use Database\Exceptions\DatabaseError;
    use Exception;

    class PixelModel extends Database
    {
        protected function generateSafeFields(): array
        {
            return [
                "pixel.id",
                "x_position",
                "y_position",
                "color_id",
                "user_id",
                "last_updated",
                "number_of_time_placed"
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }

        /**
         * Get pixels in a rectangle
         * @param int $x1 The x position of the top left corner
         * @param int $y1 The y position of the top left corner
         * @param int $x2 The x position of the bottom right corner
         * @param int $y2 The y position of the bottom right corner
         * @return array The pixels in the rectangle
         * @throws DatabaseError
         */
        public function getPixelsInRectangle(int $x1, int $y1, int $x2, int $y2): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            pixel
                                        WHERE 
                                            x_position >= ?
                                            AND x_position <= ?
                                            AND y_position >= ?
                                            AND y_position <= ?",
                ["iiii", $x1, $x2, $y1, $y2]);
        }


        /**
         * Get a pixel by their ID
         * @param $id int The ID of the pixel to get
         * @return array The pixel details
         * @throws Exception If the pixel doesn't exist
         */
        public function getPixelById(int $id): array
        {
            return $this->select("SELECT 
                                            *
                                        FROM 
                                            pixel 
                                        WHERE 
                                            id = ?",
                ["i", $id]);
        }

        /**
         * Get all pixels in a rectangle after a certain datetime
         * @return array The pixels
         * @throws Exception
         **/
        public function getPixelsAfterDate(int $x1, int $y1, int $x2, int $y2, string $date): array
        {
            return $this->select("SELECT 
                                            {$this->getSafeFields()}
                                        FROM 
                                            pixel
                                        WHERE 
                                            x_position >= ?
                                            AND x_position <= ?
                                            AND y_position >= ?
                                            AND y_position <= ?
                                            AND last_updated >= ?",
                ["iiii", $x1, $x2, $y1, $y2, $date]);
        }


        /**
         * modify a pixel at a certain xy position
         *
         * @param int $x The x position of the pixel
         * @param int $y The y position of the pixel
         * @param int $color_id The color of the pixel
         * @param int $user_id The user who placed the pixel
         *
         * @throws DatabaseError
         */
        public function updatePixel(int $x, int $y, int $color_id, int $user_id): int
        {
            return $this->update("UPDATE 
                                    pixel 
                                 SET 
                                    color_id = ?,
                                    user_id = ?,
                                    last_updated = NOW(),
                                    number_of_times_placed = number_of_times_placed + 1
                                 WHERE x_position = ? AND y_position = ?",
                ["iiii", $color_id, $user_id, $x, $y]);
        }

        /**
         * delete a pixel
         *
         * @param int $msgId The id of the pixel to delete
         * @return int the id of the deleted user
         * @throws Exception If the pixel doesn't exist
         */
        public function deletePixel(int $msgId): int
        {
            return $this->delete("DELETE FROM pixel WHERE id = ?", ["i", $msgId]);
        }

        /**
         * Create a new pixel
         * @param int $x The x position of the pixel
         * @param int $y The y position of the pixel
         * @param int $color_id The color of the pixel
         * @param int $user_id The user who placed the pixel
         * @return int The id of the pixel
         * @throws DatabaseError
         * @throws PixelAlreadyExistException
         */
        public function createPixel(int $x, int $y, int $color_id, int $user_id): int
        {
            // check if the pixel already exists
            $pixel = $this->select("SELECT * FROM pixel WHERE x_position = ? AND y_position = ?", ["ii", $x, $y]);
            if (count($pixel) > 0) {
                throw new PixelAlreadyExistException("Pixel already exists");
            }
            return $this->insert("INSERT INTO 
                                            pixel 
                                                (x_position,
                                                 y_position,
                                                 color_id,
                                                 user_id,
                                                 last_updated,
                                                 number_of_times_placed) 
                                            VALUES (?, ?, ?, ?, NOW(), 1)", ["iiii", $x, $y, $color_id, $user_id]);
        }
    }

