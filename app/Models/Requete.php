<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requete extends Model
{
    protected $fillable = [
        'eleve_id','annee_scolaire_id','objet','message',
        'type','statut','reponse','traitee_par','traitee_le',
    ];
    protected $casts = ['traitee_le' => 'datetime'];

    public function eleve(): BelongsTo { return $this->belongsTo(Eleve::class); }
    public function traitePar(): BelongsTo { return $this->belongsTo(User::class, 'traitee_par'); }

    public function badgeClass(): string {
        return match($this->statut) {
            'traitee'    => 'badge-green',
            'en_cours'   => 'badge-blue',
            'rejetee'    => 'badge-red',
            default      => 'badge-amber',
        };
    }
}