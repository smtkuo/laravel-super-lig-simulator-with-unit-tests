<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Services\ChampionshipPredictionService;
use App\Services\TournamentTeamsService;
use App\Services\FixtureService;
use App\Services\TeamStandingService;
use Carbon\Carbon;

class TournamentTeamsController extends Controller
{
    public function __construct(
        protected TournamentTeamsService $tournamentTeamsService,
        protected FixtureService $fixtureService,
        protected TeamStandingService $teamStandingService,
        protected ChampionshipPredictionService $championshipPredictionService,
    ) {
    }

    /**
     * @return Factory|View
     * 
     */
    public function index(): Factory|View
    {
        $teams = $this->tournamentTeamsService->getAllTeams();

        return view('tourment_team', compact('teams'));
    }

    /**
     * @param Request $request
     * 
     * @return RedirectResponse
     * 
     */
    public function generateFixtures(Request $request): RedirectResponse
    {
        $this->fixtureService->generateFixtures();

        return redirect()->route('generatedFixtures');
    }

    /**
     * @return Factory|View
     * 
     */
    public function generatedFixtures(): Factory|View
    {
        $fixtures = $this->fixtureService->getFixturesOrderedByDate();
        $weeks = $fixtures->groupBy(function ($data) {
            $parsedDate = Carbon::parse($data->match_date);
            $weekNumber = $parsedDate->weekOfYear;
        
            $startOfYear = Carbon::createFromDate($parsedDate->year, 1, 1);
            $firstWeekNumber = $startOfYear->weekOfYear;
            
            return $weekNumber - $firstWeekNumber + 1;
        });

        return view('generated_fixtures', compact('fixtures', 'weeks'));
    }

    /**
     * @return Factory|View
     */
    public function simulate(): Factory|View
    {
        $teamStandings = $this->teamStandingService->getAllTeamStandings();
        $fixtures = $this->fixtureService->getFixturesOrderedByDate();
        $weeks = $fixtures->groupBy(function ($data) {
            $parsedDate = Carbon::parse($data->match_date);
            $weekNumber = $parsedDate->weekOfYear;
        
            $startOfYear = Carbon::createFromDate($parsedDate->year, 1, 1);
            $firstWeekNumber = $startOfYear->weekOfYear;
            
            return $weekNumber - $firstWeekNumber + 1;
        });
        $championshipPredictions = $this->championshipPredictionService->getTeamsByChampionshipPrediction();

        return view('simulation_view', compact('teamStandings', 'weeks', 'championshipPredictions'));
    }

    /**
     * @return mixed
     * 
     */
    public function playAllWeeks(): mixed
    {
        $this->fixtureService->simulateAllWeeks();
        
        return redirect()->route('simulation'); 
    }

    /**
     * @return mixed
     * 
     */
    public function playNextWeek(): mixed
    {
        $this->fixtureService->simulateOneWeek();

        return redirect()->route('simulation'); 
    }
}
