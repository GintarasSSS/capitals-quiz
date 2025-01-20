<?php

namespace App\Providers;

use App\Interfaces\CapitalsQuizRepositoryInterface;
use App\Repositories\CapitalsQuizRepository;
use Illuminate\Support\ServiceProvider;

class CapitalsQuizRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CapitalsQuizRepositoryInterface::class, CapitalsQuizRepository::class);
    }
}
