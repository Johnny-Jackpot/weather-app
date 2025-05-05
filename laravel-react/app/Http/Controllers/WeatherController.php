<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        function getWeatherData($city)
        {
            $apiKey = 'abc123weatherapikey';
            $url = "https://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error = 'Curl error: ' . curl_error($ch);
                curl_close($ch);
                return ['error' => $error];
            }

            curl_close($ch);
            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return ['error' => $data['error']['message']];
            }

            $result = [
                'city' => $data['location']['name'],
                'country' => $data['location']['country'],
                'temperature' => $data['current']['temp_c'],
                'condition' => $data['current']['condition']['text'],
                'humidity' => $data['current']['humidity'],
                'wind_speed' => $data['current']['wind_kph'],
                'last_updated' => $data['current']['last_updated'],
            ];

//            file_put_contents('weather_log.txt', date('Y-m-d H:i:s') . " - Погода в {$result['city']}: {$result['temperature']}°C, {$result['condition']}\n", FILE_APPEND);

            return $result;
        }

        $weatherData = getWeatherData('London');
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
