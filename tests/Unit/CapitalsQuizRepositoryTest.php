<?php

namespace Tests\Unit;

use App\Repositories\CapitalsQuizRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CapitalsQuizRepositoryTest extends TestCase
{
    private CapitalsQuizRepository $repository;
    private MockInterface $clientMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientMock = Mockery::mock(Client::class);
        $this->repository = new CapitalsQuizRepository($this->clientMock);
    }

    /**
     * @dataProvider testClientFailResponseData
     */
    public function testClientFailResponse(array $response): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['X-Foo' => 'Bar'],
                json_encode($response)
            )
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $repo = new CapitalsQuizRepository($client);

        Log::shouldReceive('error')->once()->with('Server error or empty countries data');

        $result = $repo->getRandomCountry();

        $this->assertArrayHasKey('country', $result);
        $this->assertArrayHasKey('options', $result);
    }

    static function testClientFailResponseData(): array
    {
        return [
            'request error received as true' => [['error' => true]],
            'request data array is empty' => [['data' => []]],
            'request body is empty' => [[]]
        ];
    }

    public function testClientIsNotCalledWhenValueInCache()
    {
        Cache::shouldReceive('rememberForever')->andReturn([]);

        $this->clientMock->shouldReceive('request')->never();

        $this->repository->getRandomCountry();
    }

    public function testGetRandomCountry()
    {
        Cache::shouldReceive('rememberForever')->andReturn([
            ['name' => 'Country1', 'capital' => 'Capital1'],
            ['name' => 'Country2', 'capital' => 'Capital2']
        ]);

        Session::shouldReceive('get')->with('countries', [])->andReturn([]);
        Session::shouldReceive('put')->with(CapitalsQuizRepository::SESSION_KEY, Mockery::any())->once();
        Session::shouldReceive('put')->with(CapitalsQuizRepository::ANSWER_KEY, Mockery::any())->once();

        $result = $this->repository->getRandomCountry();

        $this->assertArrayHasKey('country', $result);
        $this->assertArrayHasKey('options', $result);
    }

    public function testGetCapitalAnswerWhenSessionExists()
    {
        Session::shouldReceive('has')->with('capital')->andReturn(true);
        Session::shouldReceive('get')->with('capital')->andReturn('Capital1');

        $response1 = $this->repository->getCapitalAnswer('Capital1');
        $response2 = $this->repository->getCapitalAnswer('Capital2');

        $this->assertEquals(
            [
                'correct' => true,
                'capital' => 'Capital1'
            ],
            $response1
        );
        $this->assertEquals(
            [
                'correct' => false,
                'capital' => 'Capital1'
            ],
            $response2
        );
    }

    public function testGetCapitalAnswerWhenSessionDoesNotExists()
    {
        Session::shouldReceive('has')->with('capital')->andReturn(false);

        $response = $this->repository->getCapitalAnswer('Capital1');

        $this->assertEquals([], $response);
    }

    public function testExitQuiz()
    {
        Session::shouldReceive('forget')->with('countries')->once();
        Log::shouldReceive('info')->once()->with('Session key cleared.');
        $this->assertTrue($this->repository->exitQuiz());
    }
}
