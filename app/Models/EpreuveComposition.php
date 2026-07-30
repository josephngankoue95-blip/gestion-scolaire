<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpreuveComposition extends Model
{
    protected $table = 'epreuves_composition';
    protected $fillable = [
        'enseignant_id','matiere_id','classe_id','sequence_id',
        'annee_scolaire_id','titre','fichier','fichier_corrige',
    ];

    public function enseignant(): BelongsTo { return $this->belongsTo(Enseignant::class); }
    public function matiere(): BelongsTo { return $this->belongsTo(Matiere::class); }
    public function classe(): BelongsTo { return $this->belongsTo(ClasseModel::class, 'classe_id'); }
    public function sequence(): BelongsTo { return $this->belongsTo(Sequence::class); }
}