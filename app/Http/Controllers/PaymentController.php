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
        // In a real app, you would use Stripe Checkout or Payment Intents
        // Stripe::setApiKey(config('services.stripe.secret'));

        // Mocking successful payment
        $trip->update(['status' => 'completed']);

        return response()->json(['message' => 'Payment processed successfully.']);
    }
}
