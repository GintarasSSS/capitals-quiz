<?php

namespace Tests\Feature;

use App\Repositories\CapitalsQuizRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery\MockInterface;
use Tests\TestCase;

class QuizControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    private MockInterface $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock =\Mockery::mock(CapitalsQuizRepository::class);
        $this->app->instance(CapitalsQuizRepository::class, $this->repositoryMock);
    }

    public function testIndex(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('index');
    }

    public function testGetRandomCountry(): void
    {
        $this->repositoryMock
            ->shouldReceive('getRandomCountry')
            ->once()
            ->andReturn([
                'country' => 'Country1',
                'options' => ['Capital1', 'Capital2']
            ]);

        $response = $this->get('/random-country');
        $response->assertStatus(200);
        $response->assertJsonStructure(['country', 'options']);
    }

    public function testGetCapitalAnswer(): void
    {
        $this->repositoryMock
            ->shouldReceive('getCapitalAnswer')
            ->once()
            ->with('Capital1')
            ->andReturn([]);

        $response = $this->post(
            '/capital-answer',
            [
                'capital' => 'Capital1'
            ]
        );

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testExitQuiz(): void
    {
        $this->repositoryMock->shouldReceive('exitQuiz')->once()->andReturn(true);

        $response = $this->delete('/exit-quiz');
        $response->assertStatus(200);

        $response->assertJson(['success' => true]);
    }
}
