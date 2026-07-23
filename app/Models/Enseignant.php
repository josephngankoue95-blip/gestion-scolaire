<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enseignant extends Model
{
    protected $fillable = [
        'user_id', 'matricule', 'specialite', 'diplome', 'date_recrutement', 'statut',
    ];

    protected $casts = [
        'date_recrutement' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    public function affectationsAnneeActive()
    {
        return $this->affectations()
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->with([
                'matiere',
                'classe',
                'anneeScolaire'
            ]);
    }

    /**
     * Vérifie si cet enseignant a le droit de saisir des notes
     * pour cette matière dans cette classe.
     */
    public function peutSaisir(int $matiereId, int $classeId): bool
    {
        return $this->affectations()
            ->where('matiere_id', $matiereId)
            ->where('classe_id', $classeId)
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->exists();
    }

    public function classesAffectees()
    {
        return ClasseModel::whereIn('id', $this->affectationsAnneeActive()->pluck('classe_id'))->get();
    }

    public static function genererMatricule(): string
    {
        $annee = date('Y');
        $dernier = static::where('matricule', 'like', "ENS{$annee}%")->count() + 1;
        return "ENS{$annee}" . str_pad($dernier, 4, '0', STR_PAD_LEFT);
    }
}