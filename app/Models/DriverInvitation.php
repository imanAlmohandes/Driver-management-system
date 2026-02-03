<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $dates = ['expires_at'];

        protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

}
