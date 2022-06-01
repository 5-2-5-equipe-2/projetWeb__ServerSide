<?php

    namespace Models;
    require_once PROJECT_ROOT_PATH . 'Model/Database.php';

    use Auth\Exceptions\PixelAlreadyExistException;
    use Database\Exceptions\DatabaseError;
    use Exception;

    class GameModel extends Database {

        protected function generateSafeFields(): array
        {
            return [
                'games_play.id',
                'game_code',
                'soluce',
                'max_soluce_time',
                'user_id'
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }
        

        public function getGameForPlayer(int $id) : array
        {
            
        }

        public function postSoluceGame(int $id, string $soluce): array
        {

        }
    }

    ?>