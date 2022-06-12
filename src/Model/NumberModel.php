<?php

namespace Models;

require_once PROJECT_ROOT_PATH . 'Model/Database.php';

use Auth\Exceptions\PixelAlreadyExistException;
use Database\Exceptions\DatabaseError;
use Exception;
use Models\UserModel;

class NumberModel extends Database   // en fait pas nécessaire car pas de table (puisqu'il y en a qu'un seul à garder en direct)
{
    protected int $number_real;
    protected int $upperLimit;
    protected int $lowerLimit;
    protected int $number_of_times_tried;
    public bool $isGenerated = false;

    protected function generateSafeFields(): array
    {
        return [
            "guess_the_number.id",
            "guess_the_number.number_of_times_tried",
            "guess_the_number.min_value",
            "guess_the_number.max_value",
            "guess_the_number.number_to_find",
        ];
    }

    protected function generateFields(): array
    {
        return $this->generateSafeFields();
    }

    protected function generateTypes(): array
    {
        return array(
            "guess_the_number.id" => "i",
            "guess_the_number.number_of_times_tried" => "i",
            "guess_the_number.min_value" => "i",
            "guess_the_number.max_value" => "i",
            "guess_the_number.number_to_find" => "i"
        );
    }

    protected function generateTable(): string
    {
        return "guess_the_number";
    }

    protected function generateNumber(): void
    {
        if ($this->isGenerated) {
            $this->number_real = rand(1, 9999999);
            $this->number_of_times_tried = 0;
            $this->lowerLimit = 1;
            $this->upperLimit = 9999999;
            //update in database
            $this->update("UPDATE guess_the_number SET number_to_find = ?, number_of_times_tried = ?, min_value = ?, max_value = ? WHERE id = 1", ["iiii", $this->number_real, $this->number_of_times_tried, $this->lowerLimit, $this->upperLimit]);
        } else {
            $arr = $this->get([], 1);
            print_r($arr);
            $this->number_real = $arr[0]['number_to_find'];
            $this->number_of_times_tried = $arr[0]['number_of_times_tried'];
            $this->lowerLimit = $arr[0]['min_value'];
            $this->upperLimit = $arr[0]['max_value'];
        }
    }

    protected function setUpperLimit(int $newUpperLimit): void
    {
        if ($newUpperLimit < $this->upperLimit) {
            $this->upperLimit = $newUpperLimit;
        }
        //update in database
        $this->update("UPDATE guess_the_number SET max_value = ? WHERE id = 1", ["i", $this->upperLimit]);
    }

    protected function setLowerLimit(int $newLowerLimit): void
    {
        if ($newLowerLimit > $this->lowerLimit) {
            $this->lowerLimit = $newLowerLimit;
        }
        //update in database
        $this->update("UPDATE guess_the_number SET min_value = ? WHERE id = 1", ["i", $this->lowerLimit]);
    }

    protected function incrementNumberOfTries(): void
    {
        $this->number_of_times_tried++;
        //update in database
        $this->update("UPDATE guess_the_number SET number_of_times_tried = ? WHERE id = 1", ["i", $this->number_of_times_tried]);
    }

    /**
     * Attempt to guess the number
     * @param int $number
     * @param int $user_id
     * @return array
     * @throws Exception
     */
    public function guessNumber(int $number, int $user_id): array
    {
        if (!$this->isGenerated) {
            $this->generateNumber();
        }
        $arr = array();
        $number_real = $this->number_real;
        if ($number_real == $number) {
            $arr['status'] = 'success';
            $arr['message'] = 'You guessed the number! You won 10 pixels!';
            $this->generateNumber();
            //add 10 free_pixels to user
            $userModel = new UserModel();
            $userModel->addFreePixels($user_id, 10);
        } else if ($number_real > $number) {
            $arr['status'] = 'higher';
            $arr['message'] = 'The number is higher than ' . $number;
            $this->setLowerLimit($number);
        } else {
            $arr['status'] = 'lower';
            $arr['message'] = 'The number is lower than ' . $number;
            $this->setUpperLimit($number);
        }
        return $arr;
    }
}
