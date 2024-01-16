<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository extends BaseRepository
{
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection|array
     * 
     */
    public function getAllTeamsWithStandings(): Collection|array
    {
        $teams = $this->model->with('standing')->get();
        return $teams->sortByDesc(function ($team) {
            return $team->standing->point ?? 0;
        });
    }

    /**
     * @return Collection
     * 
     */
    public function getTeamsByChampionshipPrediction(): Collection
    {
        $teams = $this->model->with('championshipPrediction')->get();
        return $teams->sortByDesc(function ($team) {
            return $team->championshipPrediction->championship_probability ?? 0;
        });
    }
}
