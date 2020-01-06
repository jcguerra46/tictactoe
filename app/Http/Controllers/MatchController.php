<?php

namespace App\Http\Controllers;

use App\Match;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Services\Interfaces\MatchesServiceInterface;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatchController extends BaseController
{
    /**
     * @var MatchesServiceInterface
     */
    private $matchesService;

    /**
     * MatchController constructor.
     * @param MatchesServiceInterface $matchesService
     */
    public function __construct(MatchesServiceInterface $matchesService)
    {
        $this->matchesService = $matchesService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches()
    {
        return response()->json($this->matchesService->allMatches());
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id) {
        $match = $this->matchesService->show($id);
        $this->matchesService->checkWinner($match, Match::PLAYER_X);
        $this->matchesService->checkWinner($match, Match::PLAYER_O);
        return response()->json($match);
    }

    /**
     * Makes a move in a match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id, Request $request) {
        $position = Input::get('position');
        $player = $request->session()->get('player', 0);

        if (!$player) {
            throw new HttpException(500, 'Player not set');
        }

        $match = $this->matchesService->move($id, $position, $player);

        return response()->json($match);
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create() {
        $this->matchesService->create();
        return response()->json($this->matchesService->allMatches());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id) {
        $this->matchesService->delete($id);
        return response()->json($this->matchesService->allMatches());
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentPlayer($id, Request $request)
    {
        $slot = $request->session()->get('player', null);
        $sessionId = $request->session()->get('sessionId', session()->getId());
        if ($slot) {
            $match = $this->matchesService->show($id);
            if ($sessionId !== $match->getAttribute('player_' . $slot)) {
                throw new HttpException(500, 'Slot in use by another player');
            }
        }
        return response()->json(['player' => $slot]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerPlayer($id, Request $request)
    {
        $slot = Input::get('player');
        $match = Match::query()->find($id);
        $player = $request->session()->get('player', null);
        $sessionId = $request->session()->get('sessionId', session()->getId());
        $column = 'player_' . $slot;
        $column2 = 'player_' . ($slot === 1 ? 2 : 1);
        $slotFilled = $match->getAttribute($column);
        $slotFilled2 = $match->getAttribute($column2);
        if (!$slotFilled) {
            $request->session()->put('player', $slot);
            $request->session()->put('sessionId', (string) $sessionId);
            $match->setAttribute($column, (string) $sessionId);
            $match->save();
        }
        if ($slotFilled && $player !== $slotFilled) {
            throw new HttpException(500, "Player registered");
        }
        if (!$slotFilled && ($sessionId === $slotFilled2)) {
            throw new HttpException(500, "You cannot join the same game twice");
        }
        return response()->json($this->matchesService->show($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitMatch(Request $request)
    {
        $request->session()->put('player', null);
        return response()->json($this->matchesService->allMatches());
    }

}