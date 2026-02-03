<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class Driver extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasTranslations;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_type',
        'license_expiry_date',
        'driver_image',
    ];
    public $translatable = ['license_type'];

    public function user()
    {
        // اليوزر ممكن يكون سائق
        return $this->belongsTo(User::class);
    }

    public function trips()
    {
        // السائق عنده اكثر من رحلة
        return $this->hasMany(Trip::class);

    }

    public function fuelLogs()
    {
        // السائق عدة مرات بعبي وقود
        return $this->hasMany(FuelLog::class);
    }

    public function vehicles()
    {
        // السائق بركب عدة مركبات
        // return $this->hasMany(Vehicle::class);
        return $this->belongsToMany(Vehicle::class)
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    public function activeVehicle()
    {
        // السيارة لأي سائق حاليا متاحة .... مش هالضرورة يعني
        return $this->belongsToMany(Vehicle::class)
            ->wherePivotNull('end_date');
    }
    public function activeVehicles()
    {
        // بتفرق عن الل فوق هاي عشان ما ترجع كولكشن  وقبل ما يتعبى تاريخ انتهاء الرحلة
        return $this->belongsToMany(Vehicle::class)
            ->wherePivotNull('end_date');
    }
}
