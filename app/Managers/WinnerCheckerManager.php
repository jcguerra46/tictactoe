<?php

namespace App\Managers;

use App\Managers\Interfaces\WinnerCheckerInterface;

class WinnerCheckerManager implements WinnerCheckerInterface
{
    /**
     * @const integer
     */
    const BOARD_SIZE = 3;

    /**
     * @const array
     */
    const ROWS = [
        1 => [
            1 => 0,
            2 => 1,
            3 => 2,
        ],
        2 => [
            1 => 3,
            2 => 4,
            3 => 5,
        ],
        3 => [
            1 => 6,
            2 => 7,
            3 => 8,
        ],
    ];

    /**
     * @const array
     */
    const COLUMNS = [
        1 => [
            1 => 0,
            2 => 3,
            3 => 6,
        ],
        2 => [
            1 => 1,
            2 => 4,
            3 => 7,
        ],
        3 => [
            1 => 2,
            2 => 5,
            3 => 8,
        ],
    ];

    /**
     * @const array
     */
    const DIAGONALS = [
        1 => [
            1 => 0,
            2 => 4,
            3 => 8,
        ],
        3 => [
            1 => 2,
            2 => 4,
            3 => 6,
        ]
    ];

    /**
     * @param array $board
     * @param int $player
     * @return bool
     */
    public function winningCheck(array $board, int $player): bool
    {
        $rows = $this->checkRows($board, $player);
        $columns = $this->checkColumns($board, $player);
        $diagonals = $this->checkDiagonals($board, $player);
        if ($rows || $columns || $diagonals) {
            return true;
        }
        return false;
    }

    /**
     * @param array $board
     * @param int $player
     * @return bool
     */
    public function checkRows(array $board, int $player): bool
    {
        for($rowNumber = 1; $rowNumber <= self::BOARD_SIZE; $rowNumber++) {
            $rowStart = $this->getRowCell($rowNumber);
            for($i = $rowStart; $i < self::BOARD_SIZE + $rowStart; $i++){
                if ($board[$i] !== $player) {
                    break;
                }
                if ($i == $rowStart + 2) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param array $board
     * @param int $player
     * @return bool
     */
    public function checkColumns(array $board, int $player): bool
    {
        for($y = 1; $y <= self::BOARD_SIZE; $y++) {
            for($x = 1; $x <= self::BOARD_SIZE ; $x++){
                $position = $this->getColumnCell($x, $y);
                if ($board[$position] !== $player) {
                    break;
                }
                if ($x == self::BOARD_SIZE) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param array $board
     * @param int $player
     * @return bool
     */
    public function checkDiagonals(array $board, int $player): bool
    {
        foreach(self::DIAGONALS as $y => $row) {
            for($x = 1; $x <= self::BOARD_SIZE ; $x++){
                $position = $this->getDiagonalCell($x, $y);
                if ($board[$position] !== $player) {
                    break;
                }
                if ($x == self::BOARD_SIZE) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param int $rowNumber
     * @return mixed
     */
    private function getRowCell(int $rowNumber)
    {
        return self::ROWS[$rowNumber][1];
    }

    /**
     * @param int $x
     * @param int $y
     * @return int
     */
    private function getColumnCell(int $x, int $y): int
    {
        return self::COLUMNS[$y][$x];
    }

    /**
     * @param int $x
     * @param int $y
     * @return int
     */
    private function getDiagonalCell(int $x, int $y): int
    {
        return self::DIAGONALS[$y][$x];
    }

}