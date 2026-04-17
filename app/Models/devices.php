<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devices extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'mac_address',
        'last_seen_at',
        'is_online'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    protected $appends = ['is_online'];

    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_seen_at && !$this->is_online) {
            return false;
        } else {
            return $this->last_seen_at->greaterThan(now()->subSeconds(30));
        }

    }

    /**
     * Scope to quickly find devices that have gone silent (Outage)
     */
    public function getHasPowerAttribute(): bool
    {
        // If it pings within 5 seconds, we assume it has power.
        return $this->getIsOnlineAttribute();
    }
}
