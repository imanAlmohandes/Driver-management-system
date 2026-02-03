<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes ,HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
    public $translatable = ['name'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function driver()
    {
        // اليوزر ممكن يكون سائق
        return $this->hasOne(Driver::class);
    }
/**
 * Get the user's notifications.
 * This overrides the default relationship to use our custom Notification model.
 *
 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
 */
    // public function notifications()
    // {
    //     return $this->morphMany(DatabaseNotification::class, 'notifiable')
    //         ->orderBy('created_at', 'desc');
    // }


    // public function reports()
    // {
    //     return $this->hasMany(Report::class);
    // }

    // برضو نفس السكوب تعت  المحطات بس هناك يعرض الحالات
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

}
