<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trip;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function process(Request $request, Trip $trip)
    {
        if ($trip->client_id !== auth()->id()) {
            abort(403);
        }

        // In a real app, you would use Stripe Checkout or Payment Intents
        // Stripe::setApiKey(config('services.stripe.secret'));

        $trip->update(['payment_status' => 'paid']);

        $trip->load('client');
        if ($trip->client) {
            try {
                $admins = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
                \Illuminate\Support\Facades\Mail::to($trip->client->email)
                    ->cc($admins)
                    ->queue(new \App\Mail\TripReceiptClient($trip, $trip->client));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erreur lors de l\'envoi du reçu stripe: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Payment processed successfully.']);
        }

        return redirect()->back()->with('success', 'Paiement enregistré.');
    }
}
