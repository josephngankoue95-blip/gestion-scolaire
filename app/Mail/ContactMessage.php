<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function build()
    {
        return $this->subject('Nouveau message de contact — ' . $this->data['sujet'])
            ->replyTo($this->data['email'] ?? config('mail.from.address'))
            ->view('emails.contact');
    }
}