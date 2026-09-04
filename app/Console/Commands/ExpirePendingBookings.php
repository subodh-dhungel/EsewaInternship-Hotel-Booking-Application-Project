<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bookings:expire')]
#[Description('Expire pending bookings that have passed their expiration time')]
class ExpirePendingBookings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('booking_status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'booking_status' => 'expired'
            ]);
        
        $this->info("Expired {$expiredBookings} booking(s).");
        return Command::SUCCESS;
    }
}
