<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class MaintenanceLog extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasTranslations;
    protected $fillable = [
        'vehicle_id',
        'company_id',
        'service_type',
        'cost',
        'service_date',
        'end_date',
        'notes',
    ];
    // عشان diffForHumans() و validation أسهل.
    protected $casts = [
        'service_date' => 'date',
        'end_date'     => 'date',
    ];

    public $translatable = ['service_type', 'notes']; 
    public function vehicle()
    {
        // الصيانة بتكون ل مركبة وحدة
        return $this->belongsTo(Vehicle::class);
    }
    public function company()
    {
        // شركة وحدة الل صلحت المركبة
        return $this->belongsTo(MaintenanceCompany::class);
    }

}
