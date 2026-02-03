<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

// <-- 1. إضافة جديدة

class Station extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    use HasTranslations; // <-- 2. إضافة جديدة

    // --- 3. إضافة جديدة: تعريف الحقول القابلة للترجمة ---
    public $translatable = ['name',
        'city'];

    // --- 4. تعديل: تأكدي أن الحقل الأصلي لا يزال في fillable ---

    protected $fillable = [
        'name',
        'city',
    ];

    /**
     * --- 5. تعديل: لا نحتاج لـ casts هنا ---
     * The HasTranslations trait handles this automatically.
     */

    public function tripsFrom()
    {
        // الرحلة تبدأ من عدة اماكن
        return $this->hasMany(Trip::class, 'from_station_id');
    }

    public function tripsTo()
    {
        // توصل ل عدة اماكن
        return $this->hasMany(Trip::class, 'to_station_id');
    }

    // ارجعيلها
    // Trip::status('pending')->count(); عشان زي هيك بالفيو
    // مش مفيدة بس حلوة
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
