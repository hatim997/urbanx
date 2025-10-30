<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCnic extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'name',
        'cnic_number',
        'issue_date',
        'front_picture',
        'back_picture',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
