<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TourDestination;
use Illuminate\Http\Request;

class TourDestinationController extends Controller
{
    public function index()
    {
        $tourDestinations = TourDestination::all();
        return response()->json($tourDestinations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'destination_id' => 'required|exists:destinations,id',
            'stop_order' => 'required|integer',
        ]);

        $tourDestination = TourDestination::create($validated);
        return response()->json($tourDestination, 201);
    }

    public function show($tourId, $destinationId)
    {
        $tourDestination = TourDestination::where('tour_id', $tourId)
            ->where('destination_id', $destinationId)
            ->firstOrFail();
        return response()->json($tourDestination);
    }

    public function update(Request $request, $tourId, $destinationId)
    {
        $tourDestination = TourDestination::where('tour_id', $tourId)
            ->where('destination_id', $destinationId)
            ->firstOrFail();

        $validated = $request->validate([
            'stop_order' => 'required|integer',
        ]);

        $tourDestination->update($validated);
        return response()->json($tourDestination);
    }

    public function destroy($tourId, $destinationId)
    {
        $tourDestination = TourDestination::where('tour_id', $tourId)
            ->where('destination_id', $destinationId)
            ->firstOrFail();
        $tourDestination->delete();
        return response()->json(null, 204);
    }
}