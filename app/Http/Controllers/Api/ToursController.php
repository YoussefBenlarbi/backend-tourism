<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class ToursController extends Controller
{
    public function index()
    {
        $tours = Tour::with('destination', 'category')->get();
        return response()->json($tours);
    }

    public function show($id)
    {
        $tour = Tour::with('destination', 'category')->find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }
        return response()->json($tour);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,sold out',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'max_travelers' => 'nullable|integer',
            'difficulty_level' => 'nullable|in:easy,moderate,challenging',
        ]);

        $tour = Tour::create($request->all());
        return response()->json($tour, 201);
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,sold out',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'max_travelers' => 'nullable|integer',
            'difficulty_level' => 'nullable|in:easy,moderate,challenging',
        ]);

        $tour->update($request->all());
        return response()->json($tour);
    }

    public function destroy($id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }
        $tour->delete();
        return response()->json(['message' => 'Tour deleted successfully']);
    }
}
