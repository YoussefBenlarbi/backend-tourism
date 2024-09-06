<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\EnquiryData;
use Illuminate\Http\Request;

class EnquiryDataController extends Controller
{
    public function index()
    {
        $enquiries = EnquiryData::with('tour')->get();
        return response()->json($enquiries);
    }

    public function show($id)
    {
        $enquiry = EnquiryData::find($id);
        if (!$enquiry) {
            return response()->json(['message' => 'Enquiry not found'], 404);
        }
        return response()->json($enquiry);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'arrival_date' => 'nullable|date',
            'departure_date' => 'nullable|date',
            'number_of_travelers' => 'nullable|integer',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,processed,cancelled',
        ]);

        $enquiry = EnquiryData::create($request->all());
        return response()->json($enquiry, 201);
    }

    public function update(Request $request, $id)
    {
        $enquiry = EnquiryData::find($id);
        if (!$enquiry) {
            return response()->json(['message' => 'Enquiry not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'arrival_date' => 'nullable|date',
            'departure_date' => 'nullable|date',
            'number_of_travelers' => 'nullable|integer',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,processed,cancelled',
        ]);

        $enquiry->update($request->all());
        return response()->json($enquiry);
    }

    public function destroy($id)
    {
        $enquiry = EnquiryData::find($id);
        if (!$enquiry) {
            return response()->json(['message' => 'Enquiry not found'], 404);
        }
        $enquiry->delete();
        return response()->json(['message' => 'Enquiry deleted successfully']);
    }
}
