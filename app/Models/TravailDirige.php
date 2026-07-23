<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravailDirige extends Model
{
    protected $table = 'travaux_diriges';
    protected $fillable = [
        'enseignant_id','matiere_id','classe_id','annee_scolaire_id',
        'titre','description','contenu','fichier',
        'date_publication','date_limite_acces','publie',
    ];
    protected $casts = [
        'date_publication'   => 'datetime',
        'date_limite_acces'  => 'datetime',
        'publie'             => 'boolean',
    ];

    public function enseignant(): BelongsTo { return $this->belongsTo(Enseignant::class); }
    public function matiere(): BelongsTo    { return $this->belongsTo(Matiere::class); }
    public function classe(): BelongsTo     { return $this->belongsTo(ClasseModel::class, 'classe_id'); }

    public function estAccessible(): bool
    {
        return $this->publie
            && now()->between($this->date_publication, $this->date_limite_acces);
    }

    public function statut(): string
    {
        if (!$this->publie) return 'brouillon';
        if (now() < $this->date_publication) return 'programme';
        if (now() > $this->date_limite_acces) return 'expire';
        return 'actif';
    }
}