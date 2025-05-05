<?php

namespace App\Services;

class WeatherService
{
    public function getWeatherData(string $city): array
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
}
