<?php

namespace App\Repositories;

use App\Interfaces\CapitalsQuizRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CapitalsQuizRepository implements CapitalsQuizRepositoryInterface
{
    const CACHE_KEY = 'countries::request';
    const SESSION_KEY = 'countries';
    const NUMBER_OF_OPTIONS = 2;
    const ANSWER_KEY = 'capital';

    public function __construct(private readonly Client $client)
    {
    }

    public function getRandomCountry(): array
    {
        $result = ['country' => null, 'options' => []];
        $countries = (array) $this->getCountries();
        $countriesIDs = session(self::SESSION_KEY, []);

        if (!$countries) {
            return $result;
        }

        $randomId = $this->generateRandomCountry($countriesIDs, $countries);
        $options = $this->generateRandomCapitals($countriesIDs, $countries, $randomId);

        $result['country'] = $countries[$randomId]['name'];
        $result['options'] = $options;

        return $result;
    }

    public function getCapitalAnswer(string $answer): array
    {
        Log::info('Answer: ' . $answer);

        if (session()->has(self::ANSWER_KEY)) {
            return [
                'correct' => session()->get(self::ANSWER_KEY) === $answer,
                'capital' => session()->get(self::ANSWER_KEY)
            ];
        }

        return [];
    }

    public function exitQuiz(): bool
    {
        session()->forget(self::SESSION_KEY);

        Log::info('Session key cleared.');
        return true;
    }

    private function getCountries(): null|array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                $response = $this->client->request(
                    'GET',
                    config('api.url'),
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . config('api.token') ,
                        ]
                    ]
                );

                $resultsTmp = json_decode($response->getBody()->getContents(), true);

                if (!empty($resultsTmp['error']) || empty($resultsTmp['data'])) {
                    throw new \Exception('Server error or empty countries data');
                }

                Log::info('Countries data requested.');

                return array_filter(
                    $resultsTmp['data'],
                    fn($value) => !empty($value['name']) && !empty($value['capital'])
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return null;
            }
        });
    }

    private function generateRandomCountry(array &$countriesIDs, array $countries): int
    {
        $randomId = null;

        while (!$randomId && !in_array($randomId, $countriesIDs)) {
            $randomId = array_rand($countries);
        }

        $countriesIDs[] = $randomId;
        session()->put(self::SESSION_KEY, $countriesIDs);
        session()->put(self::ANSWER_KEY, $countries[$randomId]['capital']);

        return $randomId;
    }

    private function generateRandomCapitals(array $countriesIDs, array $countries, int $randomId): array
    {
        $options = [];

        while (count($options) < self::NUMBER_OF_OPTIONS) {
            $optionId = array_rand($countries);

            if ($randomId !== $optionId) {
                $options[] = ['capital' => $countries[$optionId]['capital']];
            }
        }

        $options[] = ['capital' => $countries[$randomId]['capital']];
        shuffle($options);

        if (count($countriesIDs) === count($countries)) {
            session()->forget(self::SESSION_KEY);
        }

        return $options;
    }
}
