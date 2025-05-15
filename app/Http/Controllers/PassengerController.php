<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Passenger; // Assuming you have a Passenger model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class PassengerController extends Controller
{
    protected Auth $firebaseAuth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.service_account'));
        $this->firebaseAuth = $factory->createAuth();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required|string', // We'll verify this against Firebase
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:passengers,email', // Consider unique email in your DB
            'phone_number' => 'required|string|max:20|unique:passengers,phone_number',
            'terms_accepted' => 'required|boolean|accepted',
            'profile_image' => 'nullable|image|max:2048',
            // Add other relevant fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uid = $request->input('uid');

        try {
            // Verify the Firebase UID
            $user = $this->firebaseAuth->getUser($uid);

            // If the user exists in Firebase, proceed to store in your database
            $passenger = new Passenger();
            $passenger->uid = $uid;
            $passenger->full_name = $request->input('full_name');
            $passenger->email = $request->input('email');
            $passenger->phone_number = $request->input('phone_number');
            $passenger->terms_accepted = $request->input('terms_accepted');

            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('passengers', 'public');
                $passenger->profile_image = $imagePath;
            }

            $passenger->save();

            return response()->json(['message' => 'Passenger registered successfully'], 201);

        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return response()->json(['error' => 'Invalid Firebase UID. User not found.'], 400);
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            return response()->json(['error' => 'Error communicating with Firebase Auth.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }
}
