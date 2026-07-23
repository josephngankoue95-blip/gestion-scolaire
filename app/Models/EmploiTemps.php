<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploiTemps extends Model
{
    protected $table = 'emplois_temps';

    protected $fillable = [
        'classe_id', 'matiere_id', 'enseignant_id', 'annee_scolaire_id',
        'jour', 'heure_debut', 'heure_fin', 'salle',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(ClasseModel::class, 'classe_id');
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function dureeFormatee(): string
    {
        return substr($this->heure_debut, 0, 5) . ' — ' . substr($this->heure_fin, 0, 5);
    }
}