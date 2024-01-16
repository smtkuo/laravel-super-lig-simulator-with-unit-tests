<?php

namespace App\Repositories;

use App\Models\TeamStanding;
use Illuminate\Database\Eloquent\Collection;

class TeamStandingRepository extends BaseRepository
{
    
    public function __construct(TeamStanding $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     * 
     */
    public function getAllTeamStandings(): Collection
    {
        return TeamStanding::all();
    }
}