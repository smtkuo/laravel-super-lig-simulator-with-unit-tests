<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Services\TournamentTeamsService;
use App\Services\FixtureService;
use App\Services\TeamStandingService;
use App\Services\ChampionshipPredictionService;

class TournamentTeamsControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected $tournamentTeamsService;
    protected $fixtureService;
    protected $teamStandingService;
    protected $championshipPredictionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tournamentTeamsService = $this->mock(TournamentTeamsService::class);
        $this->fixtureService = $this->mock(FixtureService::class);
        $this->teamStandingService = $this->mock(TeamStandingService::class);
        $this->championshipPredictionService = $this->mock(ChampionshipPredictionService::class);
    }


    public function testGenerateFixtures()
    {
        $this->fixtureService->shouldReceive('generateFixtures')->once();
        $response = $this->post('/generate-fixtures');
        $response->assertRedirect(route('generatedFixtures'));
    }

    public function testGeneratedFixtures()
    {
        $this->fixtureService->shouldReceive('getFixturesOrderedByDate')->once()->andReturn(collect([]));
        $response = $this->get('/generate-fixtures');
        $response->assertStatus(200)
            ->assertViewIs('generated_fixtures');
    }

    public function testSimulate()
    {
        $emptyEloquentCollection = new \Illuminate\Database\Eloquent\Collection([]);
        $this->teamStandingService->shouldReceive('getAllTeamStandings')
            ->once()
            ->andReturn($emptyEloquentCollection);
        $this->fixtureService->shouldReceive('getFixturesOrderedByDate')
            ->once()
            ->andReturn(collect([]));
        $this->championshipPredictionService->shouldReceive('getTeamsByChampionshipPrediction')
            ->once()
            ->andReturn(collect([]));
        $response = $this->get('/simulation');
        $response->assertStatus(200)
            ->assertViewIs('simulation_view')
            ->assertViewHasAll(['teamStandings', 'weeks', 'championshipPredictions']);
    }

    public function testPlayAllWeeks()
    {
        $this->fixtureService->shouldReceive('simulateAllWeeks')->once();
        $response = $this->get('/play-all-weeks');
        $response->assertRedirect(route('simulation'));
    }

    public function testPlayNextWeek()
    {
        $this->fixtureService->shouldReceive('simulateOneWeek')->once();
        $response = $this->get('/play-next-week');
        $response->assertRedirect(route('simulation'));
    }

    public function testResetTournament()
    {
        $this->fixtureService->shouldReceive('resetTournament')->once();
        $response = $this->get('/reset-tournament');
        $response->assertRedirect(route('simulation'));
    }
}
