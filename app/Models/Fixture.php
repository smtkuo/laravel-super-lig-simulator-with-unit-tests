<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $team_home_id
 * @property int $team_away_id
 * @property Carbon $match_date
 * @property string $location
 * @property int $home_team_goals
 * @property int $away_team_goals
 * @property int|null $winning_team_id
 * @property int|null $losing_team_id
 * @property bool $draw
 * @property bool $played
 */
class Fixture extends Model
{
    use HasFactory;

    protected $table = 'fixtures';

    protected $fillable = [
        'team_home_id',
        'team_away_id',
        'match_date',
        'location',
        'home_team_goals',
        'away_team_goals',
        'winning_team_id',
        'losing_team_id',
        'draw',
        'played'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     */
    public function homeTeam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     */
    public function awayTeam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }
}
