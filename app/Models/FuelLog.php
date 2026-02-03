<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class FuelLog extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $fillable = [
        'driver_id',
        'trip_id',
        'receipt_number',
        'station_name',
        'receipt_image_path',
        'fuel_amount',
        'fuel_cost',
        'log_date',
    ];
    public function driver()
    {
        // الوقود بتعبى لسائق واحد
        return $this->belongsTo(Driver::class);
    }

    public function trip()
    {
        // الوقود بتعبى لرحلة وحدة
        return $this->belongsTo(Trip::class);
    }
}
