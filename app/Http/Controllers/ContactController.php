<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Envoyer l'email à l'admin
            Mail::to(config('mail.from.address', 'admin@atlasandco.com'))->send(new ContactMessage($data));
            return back()->with('success', 'Votre message a bien été envoyé. Notre équipe vous répondra dans les plus brefs délais.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.')->withInput();
        }
    }
}
