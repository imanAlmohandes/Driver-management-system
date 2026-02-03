<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DriverVehicle extends Model
{
    use HasFactory;
    // عشان لو بدي اغير لقدام اسم الجدول مثلا وما يعطيني ايررورر
    protected $table = 'driver_vehicle';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'start_date',
        'end_date',
    ];
}
