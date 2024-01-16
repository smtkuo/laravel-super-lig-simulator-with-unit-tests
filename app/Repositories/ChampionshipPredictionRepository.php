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

}