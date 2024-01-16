<?php

namespace App\Services;

use App\Repositories\ChampionshipPredictionRepository;
use Illuminate\Support\Collection;
use App\Models\Team;
use App\Repositories\TeamRepository;

class ChampionshipPredictionService
{
    public function __construct(
        protected ChampionshipPredictionRepository $championshipPredictionRepository,
        protected TeamRepository $teamRepository,
    )
    {
    }

    public function getTeamsByChampionshipPrediction(): Collection
    {
        return  $this->teamRepository->getTeamsByChampionshipPrediction();
    }
}
