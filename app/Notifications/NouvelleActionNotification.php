<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NouvelleActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $titre,
        public string $message,
        public ?string $lien = null,
        public string $icone = 'bell',
        public string $couleur = 'blue'
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'titre'   => $this->titre,
            'message' => $this->message,
            'lien'    => $this->lien,
            'icone'   => $this->icone,
            'couleur' => $this->couleur,
        ];
    }
}