<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RiderResponseController extends Controller
{
    public function store(Request $request, Ride $ride)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:accept,reject',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rider = Auth::user(); // Assuming the authenticated user is the rider

        if ($request->input('action') === 'accept') {
            if ($ride->rider_id === null && $ride->status === 'pending') {
                $ride->rider_id = $rider->id;
                $ride->status = 'accepted';
                $ride->save();

                // Optionally, notify the passenger that a rider has accepted
                // You might use FCM to send a notification to the passenger

                return response()->json(['message' => 'Ride accepted successfully', 'ride' => $ride], 200);
            } else {
                return response()->json(['message' => 'Ride already accepted or no longer pending'], 409);
            }
        } elseif ($request->input('action') === 'reject') {
            // Optionally, you might want to log the rejection or trigger other logic
            // (e.g., notify the next nearby rider)
            return response()->json(['message' => 'Ride rejected'], 200);
        }

        return response()->json(['message' => 'Invalid action'], 400);
    }
}
