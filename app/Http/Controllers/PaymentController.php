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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Payment processed successfully.']);
        }

        return redirect()->back()->with('success', 'Paiement enregistré.');
    }
}
