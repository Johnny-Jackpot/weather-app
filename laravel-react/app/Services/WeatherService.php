<?php

namespace App\Services;

use App\DTO\WeatherDTO;
use App\Exceptions\Weather\WeatherException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class WeatherService
{
    protected const PATH = 'https://api.weatherapi.com/v1/current.json';

    public function getWeather(string $city): WeatherDTO
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
//            file_put_contents('weather_log.txt', date('Y-m-d H:i:s') . " - Погода в {$result['city']}: {$result['temperature']}°C, {$result['condition']}\n", FILE_APPEND);
    }
}
