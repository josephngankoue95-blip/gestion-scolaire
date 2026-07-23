<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraisScolarite extends Model
{
    protected $table = 'frais_scolarite';
    protected $fillable = [
        'annee_scolaire_id','section_id','niveau',
        'frais_inscription','tranche1','tranche2','tranche3',
        'echeance_tranche1','echeance_tranche2','echeance_tranche3',
    ];
    protected $casts = [
        'echeance_tranche1' => 'date',
        'echeance_tranche2' => 'date',
        'echeance_tranche3' => 'date',
    ];

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function totalScolarite(): float
    {
        return (float)$this->frais_inscription
             + (float)$this->tranche1
             + (float)$this->tranche2
             + (float)$this->tranche3;
    }

    /** Cherche les frais qui correspondent à une classe (par section + niveau) */
public static function pourClasse(ClasseModel $classe, ?AnneeScolaire $annee = null): ?self
{
    $annee ??= AnneeScolaire::getActive();

    $niveauNom = $classe->niveau?->nom;

    $frais = static::where('annee_scolaire_id', $annee?->id)
        ->where('section_id', $classe->section_id)
        ->where('niveau', $niveauNom)
        ->first();

    return $frais ?? static::where('annee_scolaire_id', $annee?->id)
        ->where('section_id', $classe->section_id)
        ->whereNull('niveau')
        ->first();
}

}