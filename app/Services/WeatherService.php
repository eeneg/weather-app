<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    //OpenWeather API for weather info
    public function searchWeather($lat, $long){
        $response = Http::get('api.openweathermap.org/data/2.5/weather',[
            'lat' => $lat,
            'lon' => $long,
            'appid' => env('VITE_OpenWeatherKEY'),
            'units'=>'metric'
        ]);

        $response->throw();

        return $response->object();
    }

}
