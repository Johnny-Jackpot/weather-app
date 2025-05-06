<?php

namespace App\Services;

use App\DTO\WeatherDTO;
use App\Exceptions\Weather\WeatherException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class WeatherService
{
    public const PATH = 'https://api.weatherapi.com/v1/current.json';
    public const CACHE_KEY = 'weather_';

    /**
     * @throws WeatherException
     * @throws ConnectionException
     */
    public function getWeather(string $city): WeatherDTO
    {
        $cachedWeather = Cache::get(static::CACHE_KEY . $city);
        if ($cachedWeather) {
            return $cachedWeather;
        }

        $weather = $this->fetchWeather($city);

        Cache::put(
            static::CACHE_KEY . $city,
            $weather,
            config('services.weather.cache_ttl')
        );

        return $weather;
    }

    /**
     * @throws WeatherException
     * @throws ConnectionException
     */
    protected function fetchWeather(string $city): WeatherDTO
    {
        $apiKey = config('services.weather.api_key');
        if (!$apiKey) {
            throw new InvalidArgumentException("Weather api key is not configured");
        }

        $response = Http::withQueryParameters([
            'key' => $apiKey,
            'q' => $city
        ])->get(static::PATH);

        // Determine if the response has a 400 level status code...
        if ($response->clientError()) {
            throw new WeatherException($response->json()['error']['message']);
        }

        // Determine if the response has a 500 level status code...
        if ($response->serverError()) {
            //Weather api docs does not describe 500 errors
            throw new WeatherException('Something went wrong');
        }

        $data = $response->json();

        return new WeatherDTO(
            city: $data['location']['name'],
            country: $data['location']['country'],
            temperature: $data['current']['temp_c'],
            condition: $data['current']['condition']['text'],
            humidity: $data['current']['humidity'],
            windSpeed: $data['current']['wind_kph'],
            lastUpdated: $data['current']['last_updated']
        );
    }

    public function log(WeatherDTO $weatherDTO): void
    {
        Log::channel('weather')->info('Weather in {city}: {temp}°C, {cond}', [
            'city' => $weatherDTO->city,
            'temp' => $weatherDTO->temperature,
            'cond' => $weatherDTO->condition,
        ]);
    }
}
