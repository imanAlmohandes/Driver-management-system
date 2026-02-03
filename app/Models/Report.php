<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'file_path',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'data'       => 'array',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // التقرير معمول من مستخدم (أدمن)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // حلوة بس مش مهمة 
    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'vehicles'    => 'Vehicles Report',
            'trips'       => 'Trips Report',
            'fuel'        => 'Fuel Report',
            'maintenance' => 'Maintenance Report',
            default       => 'Report',
        };
    }

}
