<?php

namespace App\DTO;

class WeatherDTO
{
    public function __construct(
        public string $city,
        public string $country,
        public float  $temperature,
        public string $condition,
        public float  $humidity,
        public float  $windSpeed,
        public string $lastUpdated,
    )
    {
    }
}
