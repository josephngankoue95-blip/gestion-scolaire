<?php
namespace App\Mail;

use App\Models\CompteGenere;
use App\Models\Etablissement;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IdentifiantsAccesMail extends Mailable
{
    use SerializesModels;

    public function __construct(public CompteGenere $compte) {}

    public function build()
    {
        $etablissement = Etablissement::instance();

        return $this->subject("Vos identifiants de connexion — {$etablissement->nom}")
            ->view('emails.identifiants-acces')
            ->with(['etablissement' => $etablissement]);
    }
}