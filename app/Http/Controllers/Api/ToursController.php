<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ToursController extends Controller
{
    public function index()
    {
        $tours = Tour::with('category')->get();
        return response()->json($tours);
    }

    public function show($id)
    {
        $tour = Tour::with('category')->find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }
        return response()->json($tour);
    }

    public function store(Request $request)
    {
        Log::info("Received request: " . json_encode($request->except('main_image_url')));

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'duration' => 'nullable|string',
            'price' => 'nullable|numeric',
            'main_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,sold out',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'max_travelers' => 'nullable|integer',
            'difficulty_level' => 'nullable|in:easy,moderate,challenging',
        ]);
        $data = $request->except('main_image_url');
        if ($request->hasFile('main_image_url')) {
            $image = $request->file('main_image_url');
            Log::info("Image file received: " . $image->getClientOriginalName());

            $imageName = date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            try {
                // Use the Storage facade to store the file
                $path = Storage::disk('public')->putFileAs('images', $image, $imageName);

                if ($path) {
                    // Store the full path including 'images/' in the database
                    $data['main_image_url'] = $path;
                    Log::info("Image stored successfully: " . $path);
                } else {
                    Log::error("Failed to store image");
                }
            } catch (\Exception $e) {
                Log::error("Error storing image: " . $e->getMessage());
                return response()->json(['error' => 'Failed to upload image'], 500);
            }
        } else {
            Log::info("No image file in request");
        }
        Log::info("Data to create tour: " . json_encode($data));

        $tour = Tour::create($data);
        Log::info("Created tour: " . json_encode($tour));
        return response()->json($tour, 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        Log::info("Updating tour with ID: {$id}");
        Log::info("Received request data: " . json_encode($request->except('main_image_url')));

        // Find the tour or fail if not found
        $tour = Tour::findOrFail($id);
        Log::info("Found tour: " . json_encode($tour));

        // Validate the request input
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'itinerary' => ['nullable', 'string'],
            'duration' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric'],
            'main_image_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
            'status' => ['required', 'in:active,inactive,sold out'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'max_travelers' => ['nullable', 'integer'],
            'difficulty_level' => ['nullable', 'in:easy,moderate,challenging'],
        ]);
        Log::info("Request validation passed");

        // Prepare data excluding the image file
        $data = $request->except('main_image_url');

        // Handle the main image file if provided
        if ($request->hasFile('main_image_url')) {
            Log::info("New image file received: " . $request->file('main_image_url')->getClientOriginalName());
            $image = $request->file('main_image_url');
            $imageName = date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            try {
                // Delete the old image if it exists
                if ($tour->main_image_url) {
                    Log::info("Deleting old image: " . $tour->main_image_url);
                    Storage::disk('public')->delete($tour->main_image_url);
                }

                // Store the new image and get the path
                $path = Storage::disk('public')->putFileAs('images', $image, $imageName);
                $data['main_image_url'] = $path;
                Log::info("New image stored successfully: " . $path);
            } catch (\Exception $e) {
                // Log the error and return an error response
                Log::error("Error storing image: " . $e->getMessage());
                return response()->json(['error' => 'Image upload failed due to an internal error. Please try again later.'], 500);
            }
        } else {
            Log::info("No new image file in request");
            // Remove main_image_url from $data if not provided
            unset($data['main_image_url']);
        }

        // Check for changes and update
        $changes = array_diff_assoc($data, $tour->toArray());
        if (!empty($changes)) {
            Log::info("Updating tour with data: " . json_encode($changes));
            $tour->update($data);
            Log::info("Tour updated successfully");
        } else {
            Log::info("No changes detected, tour not updated");
        }

        // Refresh the tour model to get the latest data
        $tour->refresh();

        // Return the updated tour with a success message
        Log::info("Returning updated tour: " . json_encode($tour));
        return response()->json([
            'success' => true,
            'tour' => $tour
        ], 200);
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
