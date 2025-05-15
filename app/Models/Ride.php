<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ride extends Model
{
    use HasFactory;
    protected $fillable = [
        'passenger_id',
        'rider_id',
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'pickup_time',
        'status',
        'fare',
        'payment_status',
    ];
protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }
}
