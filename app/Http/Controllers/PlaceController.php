<?php

namespace App\Http\Controllers;

use App\Services\PlaceSearchService;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function __construct(
        private PlaceSearchService $placeSearchService,
        private WeatherService $weatherSearchService,
    )
    {}

    //Autocomplete API function for place suggestion
    public function searchAutoCompleteAPI(Request $request){
        return $this->placeSearchService->autoCompleteSearch($request->input('near'), $request->input('query'));
    }

    //Place details API function for place information (e.g. location, coordinates), weather, and photos
    public function searchPlaceDetailsAPI(Request $request){
        $related = $this->placeSearchService->relatedVenueSearch($request->input('lat').','.$request->input('long'));
        $photos = $this->placeSearchService->placeGetPhotos($request->input('query'));
        $weather = $this->weatherSearchService->searchWeather($request->input('lat'), $request->input('long'));

        return collect(['related' => $related, 'photos' =>$photos, 'weather' => $weather]);
    }

    //Navigation API for map directions
    public function searchPlaceDirectionAPI(Request $request){
        return $this->placeSearchService->placeDirectionSearch($request->input('from'), $request->input('to'), $request->input('mode'));
    }
}
