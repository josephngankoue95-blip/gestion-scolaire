<?php
namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureStatutMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Candidature $candidature, public string $statutLibelle) {}

    public function build()
    {
        return $this->subject("Statut de votre candidature — {$this->statutLibelle}")
            ->view('emails.candidature-statut');
    }
}