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
        $destinations = Destination::all();
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

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);
        Log::info("Full request data: " . json_encode($request->all()));
        // Log the received request data excluding the image
        Log::info("Received request: " . json_encode($request->except('image_url')));

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|file|image|max:2048',
        ]);

        // Only select the necessary fields
        $updateData = $request->only(['name', 'title', 'description']);

        DB::transaction(function () use ($request, $destination, &$updateData) {
            if ($request->hasFile('image_url')) {
                // Delete the old image if it exists
                if ($destination->image_url) {
                    Storage::disk('public')->delete($destination->image_url);
                }
                // Store the new image
                $image = $request->file('image_url');
                $imageName = date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = Storage::disk('public')->putFileAs('images', $image, $imageName);

                // Ensure $imagePath is a string
                if (is_string($imagePath)) {
                    $updateData['image_url'] = $imagePath;
                } else {
                    Log::error("Image path is not a string: " . json_encode($imagePath));
                }
            }

            // Log the update data to verify its structure before updating
            Log::info("Update data: " . json_encode($updateData));

            // Update the destination with the correct data
            $destination->update($updateData);
        });

        return response()->json([
            'message' => 'Destination updated successfully',
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
