<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'vehicle_type_id',
        'pickup_location',
        'dropoff_location',
        'distance_km',
        'duration_minutes',
        'subtotal',
        'discount_amount',
        'total_fare',
        'status',
        'requested_at',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
    ];
}
