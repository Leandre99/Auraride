<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Trip;
use App\Models\Rental;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function downloadTripInvoice(Trip $trip)
    {
        $taxRate = 0.10;
        $totalAmount = $trip->price ?? 0;
        $netAmount = $totalAmount / (1 + $taxRate);
        $taxAmount = $totalAmount - $netAmount;

        $description = 'Course: N/A - N/A';

        $data = [
            'client' => $trip->client,
            'clientName' => 'Client',
            'invoiceNumber' => 'INV-TRP-' . strtoupper(Str::random(8)),
            'date' => $trip->created_at,
            'description' => $description,
            'netAmount' => $netAmount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->download('facture-course-' . $trip->id . '.pdf');
    }

    public function downloadRentalInvoice(Rental $rental)
    {
        $taxRate = 0.10;
        $totalAmount = $rental->total_price ?? 0;
        $netAmount = $totalAmount / (1 + $taxRate);
        $taxAmount = $totalAmount - $netAmount;

        $description = 'Location: N/A - N/A';

        $data = [
            'client' => $rental->user,
            'clientName' => 'Client',
            'invoiceNumber' => 'INV-LOC-' . strtoupper(Str::random(8)),
            'date' => $rental->created_at,
            'description' => $description,
            'netAmount' => $netAmount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->download('facture-location-' . $rental->id . '.pdf');
    }
}
