import {Input} from "@/components/ui/input";
import {Button} from "@/components/ui/button";
import React, {useState} from "react";

interface WeatherFormProps {
    onSubmit: (city: string) => void,
    disableSubmit: boolean
}

export const WeatherForm = ({onSubmit, disableSubmit}: WeatherFormProps) => {
    const [city, setCity] = useState<string>("");
    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setCity(e.target.value);
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!city.length || disableSubmit) {
            return;
        }

        onSubmit(city);
    };

    return (
        <form className="flex justify-between gap-x-4 w-full" onSubmit={handleSubmit}>
            <Input
                type="text"
                value={city}
                onChange={handleInputChange}
                placeholder="Enter city"
            />
            <Button variant="outline" type="submit" disabled={disableSubmit}>Search</Button>
        </form>
    );
}
