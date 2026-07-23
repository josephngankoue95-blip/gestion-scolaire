<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementEleve extends Model
{
    protected $table = 'paiements_eleves';

    protected $fillable = [
        'inscription_scolarite_id', 'eleve_id', 'type',
        'montant', 'date_paiement', 'recu_numero', 'observation', 'enregistre_par',
    ];

    protected $casts = ['date_paiement' => 'date'];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(InscriptionScolarite::class, 'inscription_scolarite_id');
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function enregistrePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }
}