<?php

namespace App\Repositories;

use App\Models\ChampionshipPrediction;
use App\Models\Team;
use Illuminate\Support\Collection;

class ChampionshipPredictionRepository extends BaseRepository
{
    public function __construct(ChampionshipPrediction $model)
    {
        parent::__construct($model);
    }
    /**
     * @param int $teamId
     * @param int $championshipProbability
     * 
     * @return void
     * 
     */
    public function updateOrCreatePrediction(int $teamId, int $championshipProbability): void
    {
        $this->model->updateOrCreate(
            ['team_id' => $teamId],
            ['championship_probability' => $championshipProbability]
        );
    }
}