<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_name',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'vehicle_year',
        'vehicle_plate_number',
        'vehicle_images',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
