<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\TournamentTeamsController;
use App\Services\TournamentTeamsService;
use App\Services\FixtureService;
use App\Services\TeamStandingService;
use App\Services\ChampionshipPredictionService;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TournamentTeamsControllerUnitTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    private $tournamentTeamsService;
    private $fixtureService;
    private $teamStandingService;
    private $championshipPredictionService;
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        // Mock Services
        $this->tournamentTeamsService = Mockery::mock(TournamentTeamsService::class);
        $this->fixtureService = Mockery::mock(FixtureService::class);
        $this->teamStandingService = Mockery::mock(TeamStandingService::class);
        $this->championshipPredictionService = Mockery::mock(ChampionshipPredictionService::class);

        // Create instance of TournamentTeamsController
        $this->controller = new TournamentTeamsController(
            $this->tournamentTeamsService,
            $this->fixtureService,
            $this->teamStandingService,
            $this->championshipPredictionService
        );
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGenerateFixtures()
    {
        $this->fixtureService->shouldReceive('generateFixtures')->once();
        $response = $this->controller->generateFixtures(new Request());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('generatedFixtures'), $response->headers->get('Location'));
    }

    public function testGeneratedFixtures()
    {
        $this->fixtureService->shouldReceive('getFixturesOrderedByDate')->once()->andReturn(new EloquentCollection([]));
        $response = $this->controller->generatedFixtures();
        $this->assertTrue(View::exists('generated_fixtures'));
    }

    public function testSimulate()
    {
        $this->teamStandingService->shouldReceive('getAllTeamStandings')->once()->andReturn(new EloquentCollection([]));
        $this->fixtureService->shouldReceive('getFixturesOrderedByDate')->once()->andReturn(new Collection([]));
        $this->championshipPredictionService->shouldReceive('getTeamsByChampionshipPrediction')->once()->andReturn(new Collection([]));
        $response = $this->controller->simulate();
        $this->assertTrue(View::exists('simulation_view'));
    }

    public function testPlayAllWeeks()
    {
        $this->fixtureService->shouldReceive('simulateAllWeeks')->once();
        $response = $this->controller->playAllWeeks();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('simulation'), $response->headers->get('Location'));
    }

    public function testPlayNextWeek()
    {
        $this->fixtureService->shouldReceive('simulateOneWeek')->once();
        $response = $this->controller->playNextWeek();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('simulation'), $response->headers->get('Location'));
    }

    public function testResetTournament(): void
    {
        $this->fixtureService->shouldReceive('resetTournament')->once();
        $response = $this->controller->resetTournament();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('simulation'), $response->headers->get('Location'));
    }
}