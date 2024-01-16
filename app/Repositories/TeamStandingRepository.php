<?php

namespace App\Repositories;

use App\Models\TeamStanding;
use Illuminate\Database\Eloquent\Collection;

class TeamStandingRepository
{
    /**
     * @return Collection
     * 
     */
    public function getAllTeamStandings(): Collection
    {
        return TeamStanding::all();
    }
}