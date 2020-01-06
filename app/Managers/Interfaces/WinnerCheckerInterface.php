<?php

namespace App\Managers\Interfaces;

interface WinnerCheckerInterface {

    /**
     * @param array $board
     * @param int $player
     * @return bool
     */
    public function winningCheck(array $board, int $player): bool;
}