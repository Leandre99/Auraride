<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnpaidTripAlertAdmin;
use Carbon\Carbon;

class CheckUnpaidRides extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rides:check-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les courses terminées mais non payées après 24h et alerte les admins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subHours(24);

        $trips = Trip::where('status', 'completed')
            ->where('payment_status', 'pending')
            ->where('updated_at', '<', $threshold)
            ->whereNull('alert_sent_at')
            ->with(['client', 'driver'])
            ->get();

        if ($trips->isEmpty()) {
            $this->info('Aucune course en attente de paiement depuis plus de 24h.');
            return;
        }

        $adminEmail = env('ADMIN_EMAIL', config('mail.admin_email', 'admin@atlasandco.com'));

        foreach ($trips as $trip) {
            $hours = $trip->updated_at->diffInHours(Carbon::now());
            
            Mail::to($adminEmail)->send(new UnpaidTripAlertAdmin($trip, $hours));
            
            $trip->update(['alert_sent_at' => Carbon::now()]);
            
            $this->info("Alerte envoyée pour la course #{$trip->id}");
        }
    }
}
