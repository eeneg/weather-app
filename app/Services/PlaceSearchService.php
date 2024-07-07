<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PlaceSearchService
{
    public function autoCompleteSearch($near, $query){
        $response = Http::withHeaders([
            'Authorization' => env('VITE_FourSquareKEY'),
            'accept' => 'application/json'
        ])->get('https://api.foursquare.com/v3/autocomplete?query='.$query.'&ll='.$near.'&radius=10000');

        $response->throw();

        return $response;
    }

    public function placeGetPhotos($query){
        $response = Http::withHeaders([
            'Authorization' => env('VITE_FourSquareKEY')
        ])->get('https://api.foursquare.com/v3/places/'.$query.'/photos');

        $response->throw();

        return $response->object();
    }


    public function relatedVenueSearch($query){
        $response = Http::get('https://api.foursquare.com/v2/venues/trending', [
            'client_id' => env('VITE_FourSquareCLientID'),
            'client_secret' => env('VITE_FourSquareCLientSecretID'),
            'll' => $query,
            'v' => '20231010',
            'radius' => 10000,
            'limit' => 20,
        ]);

        $response->throw();

        return $response->object();
    }

    public function placeDirectionSearch($from, $to, $mode){
        $response = Http::get('https://api.mapbox.com/directions/v5/mapbox/'
            .$mode.'/'.
            $from['longitude'].','.
            $from['latitude'].';'.
            $to['longitude'].','.
            $to['latitude'],
            [
                'steps' => 'true',
                'geometries' =>'geojson',
                'access_token' => env('VITE_MapBoxKEY')
            ]
        );

        $response->throw();

        return $response->object();
    }
}
