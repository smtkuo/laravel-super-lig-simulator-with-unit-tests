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
        return $this->model->with('standing')->get();
    }
    
    /**
     * @return Collection
     * 
     */
    public function getTeamsByChampionshipPrediction(): Collection
    {
        return $this->model->with('championshipPrediction')->get();
    }
}