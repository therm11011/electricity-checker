<?php

namespace App\Console\Commands;

use App\Models\devices;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-power-status')]
#[Description('Command description')]
class CheckPowerStatus extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'check:power';
    public function handle()
    {
        $this->info('Checking power...');
        // If a device hasn't been seen in 2 minutes, it's likely a power outage
        $threshold = now()->subMinutes(1);


        $offlineDevices = devices::where('is_online', true)
            ->where('last_seen_at', '<', $threshold)
            ->get();

        foreach ($offlineDevices as $device) {
            $device->update(['is_online' => false]);

            // Trigger your notification here (Pusher, Mail, etc.)
            $this->info("ALERT: Power outage detected at {$device->device_name}");
        }
    }
}
