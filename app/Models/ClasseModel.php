<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClasseModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'nom',
        'niveau_id',
        'section_id',
        'annee_scolaire_id',
        'capacite_max',
        'professeur_principal_id',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    public function professeurPrincipal(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class, 'professeur_principal_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'classe_id');
    }

    public function emploisTemps(): HasMany
    {
        return $this->hasMany(EmploiTemps::class, 'classe_id');
    }

    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'classe_matiere', 'classe_id', 'matiere_id')
            ->withPivot('coefficient', 'ordre', 'groupe')
            ->orderByPivot('groupe')
            ->orderByPivot('ordre');
    }

    public function eleves(): HasMany
    {
        return $this->hasMany(Eleve::class, 'classe_id');
    }

    public function effectif(): int
    {
        return $this->eleves()->count();
    }
}