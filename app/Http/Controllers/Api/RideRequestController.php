<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RideRequestController extends Controller
{
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passenger_id' => 'required|exists:passengers,id',
            'origin_latitude' => 'required|numeric',
            'origin_longitude' => 'required|numeric',
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'pickup_time' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ride = Ride::create($request->all());
        $originLat = $request->input('origin_latitude');
        $originLng = $request->input('origin_longitude');
        $radius = 5; // Kilometers

        $nearbyRiders = Rider::query()
            ->where('availability_status', true)
            ->selectRaw(
                '*, ( 6371 * acos( cos( radians(?) ) * cos( radians( current_latitude ) ) * cos( radians( current_longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( current_latitude ) ) ) ) AS distance',
                [$originLat, $originLng, $originLat]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        // Now $nearbyRiders contains a collection of available riders within the specified radius, ordered by distance.

        // Next, you would implement logic to notify these riders.

        return response()->json(['message' => 'Ride requested successfully', 'ride_id' => $ride->id, 'nearby_riders' => $nearbyRiders], 201);
    }
}
