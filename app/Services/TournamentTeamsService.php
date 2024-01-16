<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;

class TournamentTeamsService
{
    public function __construct(protected TeamRepository $teamRepository)
    {
    }

    /**
     * @return Collection
     * 
     */
    public function getAllTeams(): Collection
    {
        return $this->teamRepository->all();
    }
}
