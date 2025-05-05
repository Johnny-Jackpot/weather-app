<?php


use App\Services\WeatherService;
use Illuminate\Support\Facades\Cache;

test('Weather page is reachable', function () {
    $this->get('/')
        ->assertStatus(200);
});

describe('Weather json route', function () {
    test('Requires city query', function () {
        $this->get('/weather')
            ->assertStatus(400)
            ->assertExactJson(['error' => 'Invalid city provided']);
    });

    test('Returns proper data', function () {
        $this->get('/weather?city=London')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'city',
                    'country',
                    'temperature',
                    'condition',
                    'humidity',
                    'windSpeed',
                    'lastUpdated',
                ]
            ]);
    });

    test('Stores data in cache', function () {
        Cache::flush();

        $cacheKey = WeatherService::CACHE_KEY . 'London';

        expect(Cache::has($cacheKey))->toBeFalse();

        $this->get('/weather?city=London')
            ->assertStatus(200);

        expect(Cache::has($cacheKey))->toBeTrue();

        Cache::forget($cacheKey);
    });
});




