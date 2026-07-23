<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eleve extends Model
{
    protected $fillable = [
        'matricule', 'nom', 'prenom', 'date_naissance', 'lieu_naissance',
        'sexe', 'photo', 'telephone_parent',
        'email_parent', 'adresse', 'parent_user_id', 'eleve_user_id',
        'statut', 'classe_id',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function eleveUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'eleve_user_id');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(ClasseModel::class, 'classe_id');
    }

    public function scolarites(): HasMany
    {
        return $this->hasMany(Scolarite::class, 'eleve_id');
    }

    public function requetes(): HasMany
    {
        return $this->hasMany(Requete::class, 'eleve_id');
    }

    public function nomComplet(): string
    {
        return "{$this->nom} {$this->prenom}";
    }

    public function scolariteActive(): ?Scolarite
    {
        return $this->scolarites()
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->with('classe.section', 'zoneTransport')
            ->first();
    }

        /** Scolarité pour une année précise (historique) */
    public function scolaritePourAnnee(int $anneeScolaireId): ?Scolarite
    {
        return $this->scolarites()
            ->where('annee_scolaire_id', $anneeScolaireId)
            ->with('classe.section', 'zoneTransport')
            ->first();
    }

        /** Historique complet, trié du plus récent au plus ancien */
    public function historiqueScolarite()
    {
        return $this->scolarites()
            ->with('classe.section', 'anneeScolaire')
            ->join('annees_scolaires', 'scolarites.annee_scolaire_id', '=', 'annees_scolaires.id')
            ->orderByDesc('annees_scolaires.date_debut')
            ->select('scolarites.*');
    }

    public static function genererMatricule(): string
    {
        $annee = date('Y');

        $dernier = self::where('matricule', 'like', "ELV{$annee}%")
            ->orderByDesc('id')
            ->first();

        if (!$dernier) {
            return "ELV{$annee}0001";
        }

        $numero = (int) substr($dernier->matricule, -4);

        return "ELV{$annee}" . str_pad($numero + 1, 4, '0', STR_PAD_LEFT);
    }
}