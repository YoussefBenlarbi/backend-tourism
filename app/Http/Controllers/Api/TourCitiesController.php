<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\TourCity;
use Illuminate\Http\Request;

class TourCitiesController extends Controller
{
    public function index()
    {
        $tourCities = TourCity::with('tour', 'city')->get();
        return response()->json($tourCities);
    }

    public function show($tourId, $cityId)
    {
        $tourCity = TourCity::where('tour_id', $tourId)->where('city_id', $cityId)->first();
        if (!$tourCity) {
            return response()->json(['message' => 'TourCity not found'], 404);
        }
        return response()->json($tourCity);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'city_id' => 'required|exists:cities,id',
            'stop_order' => 'nullable|integer',
        ]);

        $tourCity = TourCity::create($request->all());
        return response()->json($tourCity, 201);
    }

    public function update(Request $request, $tourId, $cityId)
    {
        $tourCity = TourCity::where('tour_id', $tourId)->where('city_id', $cityId)->first();
        if (!$tourCity) {
            return response()->json(['message' => 'TourCity not found'], 404);
        }

        $request->validate([
            'stop_order' => 'nullable|integer',
        ]);

        $tourCity->update($request->all());
        return response()->json($tourCity);
    }

    public function destroy($tourId, $cityId)
    {
        $tourCity = TourCity::where('tour_id', $tourId)->where('city_id', $cityId)->first();
        if (!$tourCity) {
            return response()->json(['message' => 'TourCity not found'], 404);
        }
        $tourCity->delete();
        return response()->json(['message' => 'TourCity deleted successfully']);
    }
}
