<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Team
 *
 * @property int $id
 * @property string $name
 * @property int $power
 * @property string $team_stadium
 * @property-read TeamStanding $standing
 * @property-read ChampionshipPrediction $championshipPrediction
 */
class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = ['name', 'power', 'team_stadium'];

    /**
     * @return HasOne
     * 
     */
    public function standing(): HasOne
    {
        return $this->hasOne(TeamStanding::class, 'team_id');
    }

    /**
     * @return HasOne
     * 
     */
    public function championshipPrediction(): HasOne
    {
        return $this->hasOne(ChampionshipPrediction::class, 'team_id');
    }
}
