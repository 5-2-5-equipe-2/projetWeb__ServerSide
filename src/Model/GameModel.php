<?php

namespace Models;

require_once PROJECT_ROOT_PATH . 'Model/Database.php';

use Auth\Exceptions\GameAlreadyPlayException;
use Auth\Exceptions\NoGamePlayableException;
use Auth\Exceptions\WrongSoluceGameException;
use Auth\Exceptions\TimeoutSoluceGameException;
use Exception;

class GameModel extends Database
{

    const TIME_BETWEEN_GAME = 0; // 5 minutes

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

    public function getGameList(int $difficulty = 0, int $times = 0): array
    {
        $resp = $this->select("SELECT *
                                        FROM 
                                            games_list WHERE difficulty >= ? AND times >= ?", ['ii', $difficulty, $times]);
        return $resp;
    }

    /**
     * Get a game
     * @param $id int The ID of the user
     * @return array The game details
     * @throws Exception If the game doesn't exist
     */

    public function getGameForPlayer(int $id, int $difficulty = 0, int $times = 0): array
    {
        $cnt = $this->select("SELECT COUNT(*) AS cnt
                                        FROM 
                                            games_play WHERE user_id = ?", ['i', $id]);
        if ($cnt[0]['cnt'] > 0) {
            throw new GameAlreadyPlayException();
        }
        $resp = $this->select("SELECT next_time_game
                                        FROM 
                                             user WHERE id = ?", ["i", $id]);
        $now = new \DateTime();
        $resp = $resp[0];
        if ($resp["next_time_game"] == NULL) {
            $this->update("UPDATE user SET next_time_game = NOW() WHERE id = ?", ["i", $id]);
            $resp2 = $this->getGameList(difficulty: $difficulty, times: $times);
            $n = rand(0, count($resp2) - 1);
            $soluce = $this->select("SELECT * FROM games_soluce WHERE game_code = ?", ["i", $resp2[$n]["code"]]);
            $m = rand(0, count($soluce) - 1);
            $now->setTimestamp($now->getTimestamp() + $resp2[$n]["times"]);
            $this->insert(
                "INSERT INTO games_play (game_code, soluce, max_soluce_time, user_id) VALUES (?, ?, ?, ?)",
                ["issi", $resp2[$n]["code"], $soluce[$m]["soluce"], $now->format('Y-m-d H:i:s'), $id]
            );
            return $resp2[$n];
        } else {
            $delta = $now->getTimestamp() - strtotime($resp["next_time_game"]);
            if ($delta < self::TIME_BETWEEN_GAME) {
                throw new GameAlreadyPlayException();
            } else {
                $this->update("UPDATE user SET next_time_game = NOW() WHERE id = ?", ["i", $id]);
                $resp2 = $this->getGameList(difficulty: $difficulty, times: $times);
                $n = rand(0, count($resp2) - 1);
                $soluce = $this->select("SELECT * FROM games_soluce WHERE game_code = ?", ["i", $resp2[$n]["code"]]);
                $m = rand(0, count($soluce) - 1);
                $now->setTimestamp($now->getTimestamp() + $resp2[$n]["times"]);
                $this->insert(
                    "INSERT INTO games_play (game_code, soluce, max_soluce_time, user_id) VALUES (?, ?, ?, ?)",
                    ["issi", $resp2[$n]["code"], $soluce[$m]["soluce"], $now->format('Y-m-d H:i:s'), $id]
                );
                return $resp2[$n];
            }
        }
    }

    public function postSoluceGame(int $id, string $soluce): array
    {
        $resp = $this->select("SELECT * FROM games_play WHERE user_id = ?", ["i", $id]);

        if (count($resp) == 0) {
            throw new NoGamePlayableException();
        }

        $resp = $resp[0];

        $now = new \DateTime();
        if ($now->getTimestamp() > strtotime($resp["max_soluce_time"])) {
            $this->delete("DELETE FROM games_play WHERE id = ?", ["i", $resp["id"]]);
            throw new TimeoutSoluceGameException();
        }

        if ($resp["soluce"] === $soluce) {
            $this->delete("DELETE FROM games_play WHERE id = ?", ["i", $resp["id"]]);
            return array("success" => true);
        } else {
            throw new WrongSoluceGameException();
        }
    }
}
