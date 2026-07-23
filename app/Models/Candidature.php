<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    protected $fillable = [
        'reference', 'nom', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe',
        'classe_demandee', 'section_id', 'etablissement_origine',
        'nom_parent', 'telephone_parent', 'email_parent', 'adresse',
        'statut', 'motif_refus', 'eleve_id', 'traite_par', 'notifie_le',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'notifie_le' => 'datetime',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CandidatureDocument::class);
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function nomComplet(): string
    {
        return "{$this->nom} {$this->prenom}";
    }

    public static function genererReference(): string
    {
        $annee = date('Y');
        $dernier = static::where('reference', 'like', "CAND{$annee}%")->count() + 1;
        return "CAND{$annee}-" . str_pad($dernier, 4, '0', STR_PAD_LEFT);
    }
}