<?php

namespace App\Http\Controllers;

use App\Exceptions\Weather\WeatherException;
use App\Services\WeatherService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WeatherController extends Controller
{
    public function index()
    {
        return Inertia::render('weather');
    }

    public function weather(Request $request, WeatherService $weatherService): JsonResponse
    {
        $city = $request->query('city');
        if (!$city) {
            return response()->json(['error' => 'Invalid city provided'], 400);
        }

        try {
            $weather = $weatherService->getWeather($city);
            $weatherService->log($weather);
            return response()->json(['data' => $weather]);
        } catch (WeatherException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (ConnectionException $e) {
            return response()->json(['error' => 'Weather API is unavailable'], 500);
        }
    }
}
