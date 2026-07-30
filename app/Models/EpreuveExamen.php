<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpreuveExamen extends Model
{
    protected $table = 'epreuves_examens';
    protected $fillable = ['matiere_id','niveau_id','annee_examen','titre','fichier','fichier_corrige','insere_par'];

    public function matiere(): BelongsTo { return $this->belongsTo(Matiere::class); }
    public function niveau(): BelongsTo { return $this->belongsTo(Niveau::class); }
    public function inserePar(): BelongsTo { return $this->belongsTo(User::class, 'insere_par'); }
}