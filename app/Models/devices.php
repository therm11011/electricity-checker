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
        'is_online' => 'boolean',
    ];

    public function getHasPowerAttribute(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->greaterThan(now()->subMinutes(2));
    }

    /**
     * Scope to quickly find devices that have gone silent (Outage)
     */
    public function scopePendingOutage($query)
    {
        return $query->where('is_online', true)
                     ->where('last_seen_at', '<', now()->subMinutes(2));
    }
}
