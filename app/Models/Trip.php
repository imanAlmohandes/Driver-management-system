<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class Trip extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasTranslations;

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'from_station_id',
        'to_station_id',
        'start_time',
        'end_time',
        'distance_km',
        'status',
        'notes',
    ];
    const STATUSES = [
        'Pending'   => 'Pending',
        'Ongoing'   => 'Ongoing',
        'Completed' => 'Completed',
        'Cancelled' => 'Cancelled',
    ];

    public $translatable = ['notes'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return __('driver.status_' . strtolower($this->status));
    }

    public function driver()
    {
        // الرحلة بتعملها مركبة وحدة
        return $this->belongsTo(Driver::class);
    }
    public function vehicle()
    {
        // الرحلة بتروحها مركبة وحدة بس
        return $this->belongsTo(Vehicle::class);
    }

    public function fromStation()
    {
        // الرحلة من
        return $this->belongsTo(Station::class, 'from_station_id');
    }

    public function toStation()
    {
        // الرحلة الى
        return $this->belongsTo(Station::class, 'to_station_id');
    }

    public function fuelLogs()
    {
        // ممكن تعبي اكثر من مرة وقود
        return $this->hasMany(FuelLog::class);
    }
}
