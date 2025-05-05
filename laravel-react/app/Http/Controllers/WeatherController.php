<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index(WeatherService $weatherService)
    {
        $weatherData = $weatherService->getWeatherData('London');
        if (isset($weatherData['error'])) {
            echo "Ошибка: " . $weatherData['error'];
        } else {
            echo "Текущая погода в {$weatherData['city']}, {$weatherData['country']}:\n";
            echo "Температура: {$weatherData['temperature']}°C\n";
            echo "Состояние: {$weatherData['condition']}\n";
            echo "Влажность: {$weatherData['humidity']}%\n";
            echo "Скорость ветра: {$weatherData['wind_speed']} км/ч\n";
            echo "Последнее обновление: {$weatherData['last_updated']}\n";
        }
    }
}
