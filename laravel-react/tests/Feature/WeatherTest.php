<?php


use App\Services\WeatherService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

        expect(Cache::has($cacheKey))->toBeFalse();
    });

    test('Retrieves data from cache on similar subsequent requests', function () {
        Cache::flush();

        $city = 'London';
        $cacheKey = WeatherService::CACHE_KEY . $city;

        expect(Cache::has($cacheKey))->toBeFalse();

        $this->get('/weather?city=' . $city)
            ->assertStatus(200);

        expect(Cache::has($cacheKey))->toBeTrue();

        Http::fake([
            WeatherService::PATH . '*' => fn() => throw new ConnectionException('Connection failed'),
        ]);

        $this->get('/weather?city=' . $city)
            ->assertStatus(200);

        Cache::forget($cacheKey);

        expect(Cache::has($cacheKey))->toBeFalse();
    });

    test('Returns error message on API error', function () {
        Http::fake([
            WeatherService::PATH . '*' => Http::response([
                'error' => ['message' => 'City not found']
            ], 400)
        ]);

        $this->get('/weather?city=London')
            ->assertStatus(500)
            ->assertExactJson(['error' => 'City not found']);
    });

    test('Returns error message on network failure', function () {
        Http::fake([
            WeatherService::PATH . '*' => fn() => throw new ConnectionException('Connection failed'),
        ]);

        $this->get('/weather?city=London')
            ->assertStatus(500)
            ->assertExactJson(['error' => 'Weather API is unavailable']);
    });
});




