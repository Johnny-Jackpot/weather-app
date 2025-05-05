<?php

namespace App\Http\Controllers;

use App\Exceptions\Weather\WeatherException;
use App\Services\WeatherService;
use Exception;

class WeatherController extends Controller
{
    public function index(WeatherService $weatherService)
    {
        try {
            $weather = $weatherService->getWeather('London');
            echo "Текущая погода в {$weather->city}, {$weather->country}:\n";
            echo "Температура: {$weather->temperature}°C\n";
            echo "Состояние: {$weather->condition}\n";
            echo "Влажность: {$weather->humidity}%\n";
            echo "Скорость ветра: {$weather->windSpeed} км/ч\n";
            echo "Последнее обновление: {$weather->lastUpdated}\n";

        } catch (WeatherException $e) {
            echo "Ошибка: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Ошибка: Что то пошло не так";
        }
    }
}
