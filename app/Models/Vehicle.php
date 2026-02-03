<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasTranslations;
    const STATUSES = [
        'Available'     => 'Available',
        'UnAvailable'   => 'UnAvailable',
        'InMaintenance' => 'InMaintenance',
    ];
    public $translatable = ['type', 'model'];
    protected $fillable  = [
        'type',
        'model',
        'plate_number',
        'status',
    ];

    public function getStatusLabelAttribute(): string
    {
        return __('driver.status_' . strtolower($this->status));
    }

    public function drivers()
    {
        // السيارة ل سائق واحد
        // return $this->belongsTo(Driver::class);
        return $this->belongsToMany(Driver::class)
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    public function maintenanceLogs()
    {
        // المركبة بتعمل عدة مرات صيانة
        return $this->hasMany(MaintenanceLog::class);
    }
    public function trips()
    {
        // المركبة بتروح عدة رحلات
        return $this->hasMany(Trip::class);
    }

}
