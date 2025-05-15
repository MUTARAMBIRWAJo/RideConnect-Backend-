<?php

namespace App\Filament\Resources\RiderResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\RiderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRider extends CreateRecord
{
    protected static string $resource = RiderResource::class;

protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure Firebase UID is present and unique.
        if (empty($data['firebase_uid'])) {
            $data['firebase_uid'] = Str::uuid(); // Generate a UUID if not provided
        }

        // Check if Firebase UID is unique (you might want to add a custom validation rule in the Form as well)
        $existingRider = \App\Models\Rider::where('firebase_uid', $data['firebase_uid'])->exists();
        if ($existingRider) {
            $data['firebase_uid'] = Str::uuid(); // Regenerate if it already exists (basic handling)
            // In a production app, you might want to inform the admin or handle this more gracefully.
        }

        // Generate a random profile picture filename if none is provided
        if (empty($data['profile_picture'])) {
            $randomFileName = 'rider_profile_' . Str::random(10) . '.png';
            // You might want to save a default placeholder image with this name
            // or just store the filename and handle the default in the frontend.
            $data['profile_picture'] = $randomFileName;
        }

        // Set a default availability status for new riders.
        if (!isset($data['availability_status'])) {
            $data['availability_status'] = false;
        }

        // Ensure email and phone number are nullable if not provided.
        $data['email'] = $data['email'] ?? null;
        $data['phone_number'] = $data['phone_number'] ?? null;
        $data['vehicle_type'] = $data['vehicle_type'] ?? null;
        $data['vehicle_model'] = $data['vehicle_model'] ?? null;
        $data['license_number'] = $data['license_number'] ?? null;
        $data['year_of_manufacture'] = $data['year_of_manufacture'] ?? null;
        $data['insurance_provider'] = $data['insurance_provider'] ?? null;
        $data['insurance_expiry_date'] = $data['insurance_expiry_date'] ?? null;
        $data['vehicle_photo_path'] = $data['vehicle_photo_path'] ?? null;
        $data['driver_license_path'] = $data['driver_license_path'] ?? null;

        return $data;
    }
}
