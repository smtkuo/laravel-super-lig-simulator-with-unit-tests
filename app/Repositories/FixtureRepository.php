<?php

namespace App\Repositories;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\{Builder,Model};
use Illuminate\Support\Collection;

class FixtureRepository extends BaseRepository
{
    public function __construct(Fixture $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection|array
     * 
     */
    public function getAllFixtures(): Collection|array
    {
        return $this->model->with(['homeTeam', 'awayTeam'])->get();
    }

    /**
     * @param int $id
     * 
     * @return Builder|Collection|Model|array|null
     * 
     */
    public function findFixture(int $id): Builder|Collection|Model|array|null
    {
        return $this->model->with(['homeTeam', 'awayTeam'])->find($id);
    }

    /**
     * @param Collection $fixtures
     * 
     * @return void
     * 
     */
    public function resetAndSaveNewFixtures(Collection $fixtures): void
    {
        $this->model->truncate();

        foreach ($fixtures as $fixture) {
            $fixture->save();
        }
    }

    /**
     * @return mixed
     * 
     */
    public function getFixturesOrderedByDate(): mixed
    {
        return $this->model->where('played', false)->orderBy('match_date', 'asc')->get();
    }
}