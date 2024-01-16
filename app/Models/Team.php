<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = ['name', 'power', 'team_stadium'];

    public function standing(): HasOne
    {
        return $this->hasOne(TeamStanding::class, 'team_id');
    }

    public function championshipPrediction(): HasOne
    {
        return $this->hasOne(ChampionshipPrediction::class, 'team_id');
    }
}
