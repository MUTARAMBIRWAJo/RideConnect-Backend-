<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rider extends Model
{
    use HasFactory;
    protected $fillable = [
        'firebase_uid',
        'full_name',
        'email',
        'phone_number',
        'profile_picture',
        'vehicle_type',
        'vehicle_model',
        'license_number',
        'availability_status',
        'year_of_manufacture',
        'insurance_provider',
        'insurance_expiry_date',
        'vehicle_photo_path',
        'driver_license_path',
        'current_latitude',
        'current_longitude',
    ];

    protected $casts = [
        'availability_status' => 'boolean',
        'insurance_expiry_date' => 'date',
    ];

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }
}
