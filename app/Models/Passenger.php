<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',           // Firebase User ID
        'full_name',
        'email',
        'phone_number',
        'profile_image', // Path to the profile image
        'terms_accepted',
        // Add any other passenger-specific fields here
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Any fields you don't want to be included in JSON responses
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'terms_accepted' => 'boolean',
        // Add any other attribute casting rules here
    ];

    /**
     * Relationships with other models can be defined here.
     * For example, if a passenger can have many rides:
     *
     * public function rides()
     * {
     * return $this->hasMany(Ride::class);
     * }
     */
}
