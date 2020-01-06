<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    const PLAYER_X = 1;
    const PLAYER_O = 2;
    const BOARD_INIT = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    const BOARD_SIZE = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'name',
        'next',
        'winner',
        'player_1',
        'player_2',
        'board'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'board' => 'array'
    ];

    /**
     * Match constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setAttribute('next', rand(self::PLAYER_X, self::PLAYER_O));
        $this->setAttribute('board', self::BOARD_INIT);
    }

    /**
     * Relation belongsTo witn user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return 'Match ' . $this->getAttribute('id');
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        foreach($this->getAttribute('board') as $position) {
            if ($position === 0) {
                return false;
            }
        }
        return true;
    }
}
