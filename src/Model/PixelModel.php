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
                "pixel.x_position",
                "pixel.y_position",
                "pixel.color_id",
                "pixel.user_id",
                "pixel.last_updated",
                "pixel.number_of_times_placed"
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }

        protected function generateTypes(): array
        {
            return array(
                "pixel.id" => "i",
                "pixel.x_position" => "i",
                "pixel.y_position" => "i",
                "pixel.color_id" => "i",
                "pixel.user_id" => "i",
                "pixel.last_updated" => "s",
                "pixel.number_of_times_placed" => "i"
            );
        }

        protected function generateTable(): string
        {
            return "pixel";
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
        public function getPixelsInRectangleAfterDate(int $x1, int $y1, int $x2, int $y2, string $date): array
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
                ["iiiis", $x1, $x2, $y1, $y2, $date]);
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
        public function deletePixel(int $pixelId): int
        {
            return $this->delete("DELETE FROM pixel WHERE id = ?", ["i", $pixelId]);
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

        /**
         * Get all pixels in a rectangle and return a 2D array of the pixels
         *
         *
         * @param int $x1 The x position of the top left corner
         * @param int $y1 The y position of the top left corner
         * @param int $x2 The x position of the bottom right corner
         * @param int $y2 The y position of the bottom right corner
         * @return array
         * @throws DatabaseError
         */
        public function getPixelsInRectangleAsArray(int $x1, int $y1, int $x2, int $y2): array
        {
            $pixels = $this->getPixelsInRectangle($x1, $y1, $x2, $y2);
            $pixelsArray = [];
            foreach ($pixels as $pixel) {
                $pixelsArray[$pixel["x_position"]][$pixel["y_position"]] = $pixel;
            }
            // convert the array to a 2D array
            $pixelsArray = array_map(function ($row) {
                return array_values($row);
            }, $pixelsArray);
            $finalArray = [];
            foreach ($pixelsArray as $row) {
                $finalArray[] = array_values($row);
            }
            return $finalArray;
        }


        public function makeUserIdNull(int $pixelId): int
        {
            return $this->update("UPDATE pixel SET user_id = null WHERE id = ?", ["i", $pixelId]);
        }

        public function getPixelsByUserId(int $userId): array
        {
            return $this->select("SELECT * FROM pixel WHERE user_id = ?", ["i", $userId]);
        }

        public function getPixels(int $limit): array
        {
            return $this->select("SELECT * FROM pixel ORDER BY id LIMIT ?", ["i", $limit]);
        }
    }

