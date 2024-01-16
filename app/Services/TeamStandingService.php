<?php

namespace App\Services;

use App\Repositories\TeamRepository;
use App\Repositories\TeamStandingRepository;
use Illuminate\Database\Eloquent\Collection;

class TeamStandingService
{
    public function __construct(
        protected TeamStandingRepository $teamStandingRepository,
        protected TeamRepository $teamRepository,
    )
    {}

    /**
     * @return Collection|array
     * 
     */
    public function getAllTeamStandings(): Collection|array
    {
        return $this->teamRepository->getAllTeamsWithStandings();
    }
}
