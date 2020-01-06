<?php

namespace App\Services;

use App\Match;
use Illuminate\Support\Collection;
use App\Managers\Interfaces\winnerCheckerInterface;
use App\Services\Interfaces\MatchesServiceInterface;

class MatchesService implements MatchesServiceInterface
{
    /**
     * @var winnerCheckerInterface
     */
    private $winnerChecker;

    /**
     * MatchesService constructor.
     * @param winnerCheckerInterface $winnerChecker
     */
    public function __construct(winnerCheckerInterface $winnerChecker)
    {
        $this->winnerChecker = $winnerChecker;
    }

    /**
     * All matches
     *
     * @return Collection
     */
    public function allMatches(): Collection
    {
        return Match::all();
    }

    /**
     * Show a match
     *
     * @param int $id
     * @return Match
     */
    public function show(int $id): Match
    {
        $match = Match::query()->findOrFail($id);
        return $match;
    }

    /**
     * Create a match
     *
     * @return Match
     */
    public function create(): Match
    {
        $match =new Match();
        $match->save();
        return $match;
    }

    /**
     * Dekete a match
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        Match::destroy($id);
    }

    /**
     * Move
     *
     * @param int $match_id
     * @param int $position
     * @param int $player
     * @return Match
     */
    public function move(int $match_id, int $position, int $player): Match
    {
        $match = Match::query()->find($match_id);
        $board = $match->getAttribute('board');
        $board[$position] = $player;
        $match->setAttribute('board', $board);
        $match->setAttribute('next', $match->getAttribute('next') === 1 ? 2 : 1);
        $match->save();
        $this->checkWinner($match, $player);
        return $match;
    }

    /**
     * Check a winner
     *
     * @param Match $match
     * @param int $player
     * @return bool
     */
    public function checkWinner(Match $match, int $player): bool
    {
        $board = $match->getAttribute('board');
        if($this->winnerChecker->winningCheck($board, $player)) {
            $match->setAttribute('winner', $player);
            $match->save();
            return true;
        }
        if ($match->isFinished()) {
            $match->setAttribute('winner', Match::BOARD_SIZE);
            $match->save();
            return true;
        }
        return false;
    }
}