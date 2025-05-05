<?php

use App\Services\WeatherService;
use Tests\TestCase;

uses(TestCase::class);

test('Weather api key is configured', function () {
    expect(config()->get('services.weather.api_key'))->not()->toBeNull();
});

test('Weather service throws exception when no api key provided', function () {
    config()->set('services.weather.api_key', null);
    $weatherService = resolve(WeatherService::class);
    $weatherService->getWeatherData('London');
})->throws(InvalidArgumentException::class, 'Weather api key is not configured');

