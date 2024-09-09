<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestinationsController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('tours')->get();
        return response()->json($destinations);
    }

    public function show($id)
    {
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }
        return response()->json($destination);
    }

    public function store(Request $request)
    {
        Log::info("Received request: " . json_encode($request->except('image_url')));

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|file|image|max:2048',
        ]);

        $data = $request->except('image_url');

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            Log::info("Image file received: " . $image->getClientOriginalName());

            $imageName = date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            try {
                // Use the Storage facade to store the file
                $path = Storage::disk('public')->putFileAs('images', $image, $imageName);

                if ($path) {
                    // Store the path relative to the storage/app/public directory
                    $data['image_url'] = 'images/' . $imageName;
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

        $destination = Destination::create($data);
        Log::info("Created destination: " . json_encode($destination));
        return response()->json($destination, 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        Log::info("Updating destination with ID: {$id}");
        Log::info("Received request data: " . json_encode($request->except('image_url')));
        // Find the destination or fail if not found
        $destination = Destination::findOrFail($id);
        Log::info("Found destination: " . json_encode($destination));
        // Validate the request input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
        ]);
        Log::info("Request validation passed");
        // Prepare data excluding the image file
        $data = $request->except('image_url');
        // Handle the image file if provided
        if ($request->hasFile('image_url')) {
            Log::info("New image file received: " . $request->file('image_url')->getClientOriginalName());
            $image = $request->file('image_url');
            $imageName = date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            try {
                // Delete the old image if it exists
                if ($destination->image_url) {
                    Log::info("Deleting old image: " . $destination->image_url);
                    Storage::disk('public')->delete($destination->image_url);
                }

                // Store the new image and get the path
                $path = Storage::disk('public')->putFileAs('images', $image, $imageName);
                $data['image_url'] = $path;
                Log::info("New image stored successfully: " . $path);
            } catch (\Exception $e) {
                // Log the error and return an error response
                Log::error("Error storing image: " . $e->getMessage());
                return response()->json(['error' => 'Image upload failed due to an internal error. Please try again later.'], 500);
            }
        } else {
            Log::info("No new image file in request");
            // Remove image_url from $data if not provided
            unset($data['image_url']);
        }

        // Check for changes and update
        $changes = array_diff_assoc($data, $destination->toArray());
        if (!empty($changes)) {
            Log::info("Updating destination with data: " . json_encode($changes));
            $destination->update($data);
            Log::info("Destination updated successfully");
        } else {
            Log::info("No changes detected, destination not updated");
        }

        // Refresh the destination model to get the latest data
        $destination->refresh();

        // Return the updated destination with a success message
        Log::info("Returning updated destination: " . json_encode($destination));
        return response()->json([
            'success' => true,
            'destination' => $destination
        ], 200);
    }


    public function destroy($id)
    {
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }
        $destination->delete();
        return response()->json(['message' => 'Destination deleted successfully']);
    }
}
