<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'name',
        'license_number',
        'address',
        'front_picture',
        'back_picture',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
