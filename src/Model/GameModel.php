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
                'games_play.game_code',
                'games_play.soluce',
                'games_play.max_soluce_time',
                'games_play.user_id'
            ];
        }

        protected function generateFields(): array
        {
            return $this->generateSafeFields();
        }

        protected function generateTypes(): array
        {
            return array(
                'games_play.id' => 'i',
                'games_play.game_code' => 'i',
                'games_play.soluce' => 's',
                'games_play.max_soluce_time' => 's',
                'games_play.user_id' => 'i'
            );
        }

        protected function generateTable(): string
        {
            return "games_play";
        }

        public function getGameList() : array
        {
            $resp = $this->select("SELECT *
                                        FROM 
                                            games_list");
            return $resp;
        }

        /**
         * Get a game
         * @param $id int The ID of the user
         * @return array The game details
         * @throws Exception If the game doesn't exist
         */

        public function getGameForPlayer(int $id) : array
        {
            $resp = $this->select("SELECT *
                                        FROM 
                                            next_time_game");
            
            $resp2 = $this->getGameList();
            $n = rand(0, count($resp2) - 1);
            return $resp2[$n];
        }

        public function postSoluceGame(int $id, string $soluce): array
        {
            return array();
        }
    }

    ?>