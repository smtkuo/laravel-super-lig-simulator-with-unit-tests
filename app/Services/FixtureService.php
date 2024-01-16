<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\TeamStanding;
use App\Repositories\ChampionshipPredictionRepository;
use Illuminate\Support\Collection;
use App\Repositories\FixtureRepository;
use App\Repositories\TeamStandingRepository;
use Carbon\Carbon;

class FixtureService
{
    public function __construct(
        protected FixtureRepository $fixtureRepository,
        protected TeamStandingRepository $teamStandingRepository,
        protected ChampionshipPredictionRepository $championshipPredictionRepository
    ) {
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
                        'location' => [$homeTeam->team_stadium, $awayTeam->team_stadium][rand(0, 1)],
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

    /**
     * @return void
     * 
     */
    public function simulateAllWeeks(): void
    {
        $fixtures = $this->fixtureRepository->model()->where('played', false)->get();

        foreach ($fixtures as $fixture) {
            $this->simulateMatch($fixture);
        }
        
        $this->calculateChampionshipPredictions();
    }

    /**
     * @return void
     * 
     */
    public function simulateOneWeek(): void
    {
        $fixtures = $this->fixtureRepository->getFixturesOrderedByDate();
        $weeks = $fixtures->where('played',false)->groupBy(function ($data) {
            $parsedDate = Carbon::parse($data->match_date);
            $weekNumber = $parsedDate->weekOfYear;
        
            $startOfYear = Carbon::createFromDate($parsedDate->year, 1, 1);
            $firstWeekNumber = $startOfYear->weekOfYear;
            
            return $weekNumber - $firstWeekNumber + 1;
        });


        foreach ($weeks as $fixtures) {
            foreach ($fixtures as $fixture) {
                $this->simulateMatch($fixture);
            }
            break;
        }
        
        $this->calculateChampionshipPredictions();
    }

    /**
     * @param Fixture $fixture
     * 
     * @return void
     * 
     */
    public function simulateMatch(Fixture $fixture): void
    {
        $homeTeamPower = $fixture->homeTeam->power;
        $awayTeamPower = $fixture->awayTeam->power;

        $homeTeamGoals = $this->calculateGoals($homeTeamPower, $awayTeamPower);
        $awayTeamGoals = $this->calculateGoals($awayTeamPower, $homeTeamPower);

        $fixture->home_team_goals = $homeTeamGoals;
        $fixture->away_team_goals = $awayTeamGoals;
        $fixture->played = true;

        $this->determineMatchOutcome($fixture, $homeTeamGoals, $awayTeamGoals);
        $this->updateTeamStandings($fixture, $homeTeamGoals, $awayTeamGoals);

        $fixture->save();
    }

        /**
     * Calculate the championship probability for a team based on their performance.
     *
     * @param TeamStanding $standing
     * @param Collection $allStandings
     * @return int
     * 
     */
    public function calculateProbability(TeamStanding $standing, Collection $allStandings): int
    {
        $maxPoints = $allStandings->max('point');
        $maxGoalDifference = $allStandings->max('goal_difference');

        // Performans faktörleri hesaplanıyor
        $pointsFactor = ($standing->point / max($maxPoints, 1)) * 100;
        $goalDifferenceFactor = ($standing->goal_difference / max($maxGoalDifference, 1)) * 100;

        // Ortalama alınarak genel performans puanı hesaplanıyor
        $performanceScore = ($pointsFactor + $goalDifferenceFactor) / 2;

        // Performans puanını 1 ile 100 arasında bir değere dönüştür
        return max(1, min(100, (int) round($performanceScore)));
    }

    
    /**
     * @return void
     * 
     */
    public function calculateChampionshipPredictions(): void
    {
        $teamStandings = $this->teamStandingRepository->model()->all();

        foreach ($teamStandings as $standing) {
            $probability = $this->calculateProbability($standing, $teamStandings);
            
            $this->championshipPredictionRepository->model()->updateOrCreate(
                ['team_id' => $standing->team_id],
                ['championship_probability' => $probability]
            );
        }
    }

    /**
     * @param Fixture $fixture
     * @param int $homeTeamGoals
     * @param int $awayTeamGoals
     * 
     * @return void
     * 
     */
    private function determineMatchOutcome(Fixture $fixture, int $homeTeamGoals, int $awayTeamGoals): void
    {
        if ($homeTeamGoals > $awayTeamGoals) {
            $fixture->winning_team_id = $fixture->team_home_id;
            $fixture->losing_team_id = $fixture->team_away_id;
            $fixture->draw = false;
        } elseif ($homeTeamGoals < $awayTeamGoals) {
            $fixture->winning_team_id = $fixture->team_away_id;
            $fixture->losing_team_id = $fixture->team_home_id;
            $fixture->draw = false;
        } else {
            $fixture->winning_team_id = null;
            $fixture->losing_team_id = null;
            $fixture->draw = true;
        }
    }

    /**
     * @param int $teamPower
     * @param int $opponentPower
     * @return int
     */
    public function calculateGoals(int $teamPower, int $opponentPower): int
    {
        $goalChance = $teamPower / max($teamPower + $opponentPower, 1);
        $predictedGoals = (int) round($goalChance * 10);

        return min($predictedGoals, 10);
    }

    /**
     * @param Fixture $fixture
     * @param int $homeTeamGoals
     * @param int $awayTeamGoals
     * 
     * @return void
     * 
     */
    public function updateTeamStandings(Fixture $fixture, int $homeTeamGoals, int $awayTeamGoals): void
    {
        $homeTeamStanding = $this->teamStandingRepository->firstOrCreate(['team_id' => $fixture->team_home_id]);
        $awayTeamStanding = $this->teamStandingRepository->firstOrCreate(['team_id' => $fixture->team_away_id]);

        $homeTeamStanding->played++;
        $awayTeamStanding->played++;

        if ($homeTeamGoals > $awayTeamGoals) {
            $homeTeamStanding->won++;
            $awayTeamStanding->lost++;
            $homeTeamStanding->goal_difference += ($homeTeamGoals - $awayTeamGoals);
            $awayTeamStanding->goal_difference -= ($homeTeamGoals - $awayTeamGoals);
            $homeTeamStanding->point += 3;
        } elseif ($awayTeamGoals > $homeTeamGoals) {
            $awayTeamStanding->won++;
            $homeTeamStanding->lost++;
            $awayTeamStanding->goal_difference += ($awayTeamGoals - $homeTeamGoals);
            $homeTeamStanding->goal_difference -= ($awayTeamGoals - $homeTeamGoals);
            $awayTeamStanding->point += 3;
        } else {
            $homeTeamStanding->drawn++;
            $awayTeamStanding->drawn++;
            $homeTeamStanding->point++;
            $awayTeamStanding->point++;
        }

        $homeTeamStanding->save();
        $awayTeamStanding->save();
    }

    
    /**
     * @return void
     * 
     */
    public function resetTournament(): void
    {
        $this->fixtureRepository->model()->query()->update([
            'winning_team_id' => null,
            'losing_team_id' => null,
            'draw' => 0,
            'home_team_goals' => null,
            'away_team_goals' => null,
            'played' => false
        ]);
        $this->teamStandingRepository->model()->query()->delete();
        $this->championshipPredictionRepository->model()->query()->delete();
    }
}
