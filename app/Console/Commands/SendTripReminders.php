<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use Carbon\Carbon;
use App\Jobs\SendSmsJob;

class SendTripReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders for trips scheduled for today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $startWindow = $now->copy()->addMinutes(115); // Entre 1h55
        $endWindow = $now->copy()->addMinutes(125); // et 2h05 avant la course

        $trips = Trip::with(['client', 'driver'])
            ->whereNotNull('scheduled_at')
            ->whereIn('status', ['pending', 'assigned', 'accepted'])
            ->whereBetween('scheduled_at', [$startWindow, $endWindow])
            ->get();

        foreach ($trips as $trip) {
            if ($trip->client && !empty($trip->client->phone_number)) {
                $time = Carbon::parse($trip->scheduled_at)->format('H:i');
                $driverName = $trip->driver ? " (Chauffeur: {$trip->driver->name})" : "";
                
                $msg = "ATLAS VTC RAPPEL: Vous avez une course prévue aujourd'hui à {$time}{$driverName}. Pour annuler, rendez-vous sur votre espace client.";
                
                SendSmsJob::dispatch($trip->client->phone_number, $msg);
            }
        }

        $this->info(count($trips) . ' reminders sent.');
    }
}
