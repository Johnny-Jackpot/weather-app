import React from "react";
import {useFetch} from "@/hooks/use-fetch";
import {type Weather} from "@/features/weather/types";
import {WeatherCard} from "@/features/weather/components/weather-card";
import {WeatherForm} from "@/features/weather/components/weather-form";
import {Loader} from "@/components/loader";

export default function Weather() {
    const {data: weather, loading, error, getData} = useFetch<Weather>();

    const onSubmit = (city: string) => {
        getData(`/weather?city=${encodeURIComponent(city)}`);
    };

    return (
        <div className="flex flex-col items-center w-full md:w-1/2 mx-10 md:mx-auto my-10 gap-4">
            <WeatherForm onSubmit={onSubmit} disableSubmit={loading} />
            {loading && <Loader />}
            {error && <div className="text-red-500">{error}</div>}
            {weather && <WeatherCard weather={weather} />}
        </div>
    );
}
