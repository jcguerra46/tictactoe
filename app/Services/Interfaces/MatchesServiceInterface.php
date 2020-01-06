<?php

namespace App\Services\Interfaces;

use App\Match;
use Illuminate\Support\Collection;

interface MatchesServiceInterface {

    /**
     * All matches
     *
     * @return Collection
     */
    public function allMatches(): Collection;

    /**
     * Show a match
     *
     * @param int $id
     * @return Match
     */
    public function show(int $id): Match;

    /**
     * Create a match
     *
     * @return Match
     */
    public function create(): Match;

    /**
     * Delete a match
     *
     * @param int $id
     */
    public function delete(int $id): void;


    /**
     * Move
     *
     * @param  int $match_id
     * @param  int $celln
     * @param  int $player
     * @return Match
     */
    public function move(int $match_id, int $cell, int $player): Match;

    /**
     * Check a winner
     *
     * @param  Match  $match
     * @param  $player
     * @return bool
     */
    public function checkWinner(Match $match, int $player): bool;
}