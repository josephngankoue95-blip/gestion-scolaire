<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    protected $fillable = ['livre_id','eleve_id','enseignant_id','date_emprunt','date_retour_prevue','date_retour_effective','statut','enregistre_par'];
    protected $casts = ['date_emprunt'=>'date','date_retour_prevue'=>'date','date_retour_effective'=>'date'];

    public function livre(): BelongsTo { return $this->belongsTo(Livre::class); }
    public function eleve(): BelongsTo { return $this->belongsTo(Eleve::class); }
    public function enseignant(): BelongsTo { return $this->belongsTo(Enseignant::class); }
    public function enregistrePar(): BelongsTo { return $this->belongsTo(User::class, 'enregistre_par'); }

    public function emprunteurNom(): string
    {
        return $this->eleve?->nomComplet() ?? $this->enseignant?->user?->name ?? '-';
    }

    public function estEnRetard(): bool
    {
        return $this->statut === 'en_cours' && now()->gt($this->date_retour_prevue);
    }
}