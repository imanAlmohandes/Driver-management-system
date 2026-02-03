<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class MaintenanceCompany extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasTranslations;
    protected $fillable = ['name', 'phone', 'address'];

    public $translatable = ['name', 'address']; 
    public function maintenanceLogs()
    {
        // الشركة عندها عدة سجلات ل تصليح المركبات
        return $this->hasMany(MaintenanceLog::class, 'company_id');
    }
}
