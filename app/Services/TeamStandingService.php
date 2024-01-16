<?php

namespace App\Services;

use App\Repositories\{TeamRepository,TeamStandingRepository};

class TeamStandingService
{
    public function __construct(
        protected TeamStandingRepository $teamStandingRepository,
        protected TeamRepository $teamRepository,
    )
    {}

    public function getAllTeamStandings()
    {
        return $this->teamRepository->getAllTeamsWithStandings();
    }
}
