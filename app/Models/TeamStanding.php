<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $team_id
 * @property int $played
 * @property int $won
 * @property int $drawn
 * @property int $lost
 * @property int $goal_difference
 */
class TeamStanding extends Model
{
    use HasFactory;

    protected $table = 'team_standings';

    protected $fillable = ['team_id', 'played', 'won', 'drawn', 'lost', 'goal_difference'];

}
