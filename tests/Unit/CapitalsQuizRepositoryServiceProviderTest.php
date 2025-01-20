<?php

namespace Tests\Unit;

use App\Interfaces\CapitalsQuizRepositoryInterface;
use App\Repositories\CapitalsQuizRepository;
use Tests\TestCase;

class CapitalsQuizRepositoryServiceProviderTest extends TestCase
{
    public function testCapitalsQuizRepository(): void
    {
        $repository = $this->app->make(CapitalsQuizRepository::class);
        $this->assertInstanceOf(CapitalsQuizRepositoryInterface::class, $repository);
    }
}
