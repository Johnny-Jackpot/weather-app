import React from "react";
import type {Weather} from "@/features/weather/types";
import {Card, CardContent, CardHeader, CardTitle} from "@/components/ui/card";

interface WeatherProps {
    weather: Weather,
}

export const WeatherCard = ({weather}: WeatherProps) => {
    return (
      <Card>
          <CardHeader>
              <CardTitle>Weather in {weather.city}, {weather.country}</CardTitle>
          </CardHeader>
          <CardContent>
              <p>Temperature: {weather.temperature}°C</p>
              <p>Condition: {weather.condition}</p>
              <p>Humidity: {weather.humidity}%</p>
              <p>Wind Speed: {weather.windSpeed}km/h</p>
              <p>Last updated: {weather.lastUpdated}</p>
          </CardContent>
      </Card>
    );
}
