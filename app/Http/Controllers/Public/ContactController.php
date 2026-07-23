<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'sujet' => 'required|string|max:50',
            'message' => 'required|string|max:1000',
        ]);

        try {
            Mail::to(config('mail.contact_recipient', 'contact@etablissement.cm'))
                ->send(new ContactMessage($validated));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email de contact: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer ou nous contacter par téléphone.');
        }

        return back()->with('success', 'Votre message a été envoyé. Nous vous répondrons rapidement.');
    }
}