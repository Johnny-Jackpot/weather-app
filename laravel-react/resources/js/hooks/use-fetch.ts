import { useState } from "react";

export function useFetch<T>() {
    const [data, setData] = useState<T | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);

    const fetchData = async (url: string, options?: RequestInit) => {
        setLoading(true);
        setError(null);
        setData(null);

        try {
            const response = await fetch(url, {
                headers: { "Content-Type": "application/json" },
                ...options,
            });

            const json = await response.json();
            setData(response.ok ? json.data : null);
            setError(!response.ok ? json.error || "Unknown error" : null);
        } catch (e: any) {
            setError("Something went wrong. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    const getData = async function (url: string, options?: RequestInit) {
        return fetchData(url, {method: 'GET', ...options});
    }

    return { data, loading, error, fetchData, getData };
}
