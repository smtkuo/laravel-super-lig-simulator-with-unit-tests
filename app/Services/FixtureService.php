<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Support\Collection;
use App\Repositories\FixtureRepository;

class FixtureService
{
    public function __construct(
        protected FixtureRepository $fixtureRepository
    )
    {
    }

    /**
     * @return Collection
     * 
     */
    public function generateFixtures(): Collection
    {
        $teams = Team::all();
        $fixtures = collect();

        $startDate = now()->startOfWeek();
        $matchWeeks = $teams->count() - 1; 

        foreach ($teams as $homeTeam) {
            foreach ($teams as $awayTeam) {
                if ($homeTeam->id != $awayTeam->id) {
                    $matchDate = $startDate->copy()->addWeeks(rand(0, $matchWeeks))->startOfWeek()->addDays(rand(0, 6));

                    $fixture = new Fixture([
                        'team_home_id' => $homeTeam->id,
                        'team_away_id' => $awayTeam->id,
                        'match_date' => $matchDate,
                        'location' => [$homeTeam->team_stadium, $awayTeam->team_stadium][rand(0,1)],
                    ]);

                    $fixtures->push($fixture);
                }
            }
        }
        $this->fixtureRepository->resetAndSaveNewFixtures($fixtures);

        return $fixtures;
    }

    
    /**
     * @return mixed
     * 
     */
    public function getFixturesOrderedByDate(): mixed
    {
        return $this->fixtureRepository->getFixturesOrderedByDate();
    }
}
