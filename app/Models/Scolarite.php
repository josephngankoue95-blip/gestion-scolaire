<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scolarite extends Model
{
    protected $table = 'scolarites';
    protected $fillable = [
        'eleve_id','classe_id','annee_scolaire_id','zone_transport_id',
        'date_inscription','type_inscription',
        'frais_inscription','montant_tranche1','montant_tranche2','montant_tranche3','montant_transport',
        'paye_inscription','paye_tranche1','paye_tranche2','paye_tranche3','paye_transport',
    ];
    protected $casts = ['date_inscription' => 'date'];

    public function eleve(): BelongsTo { return $this->belongsTo(Eleve::class); }
    public function classe(): BelongsTo { return $this->belongsTo(ClasseModel::class, 'classe_id'); }
    public function anneeScolaire(): BelongsTo { return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id'); }
    public function zoneTransport(): BelongsTo { return $this->belongsTo(ZoneTransport::class, 'zone_transport_id'); }
    public function paiements(): HasMany { return $this->hasMany(PaiementScolaire::class, 'scolarite_id'); }

    public function totalDu(): float
    {
        return (float)$this->frais_inscription
             + (float)$this->montant_tranche1
             + (float)$this->montant_tranche2
             + (float)$this->montant_tranche3
             + (float)$this->montant_transport;
    }

    public function totalPaye(): float
    {
        return (float)$this->paye_inscription
             + (float)$this->paye_tranche1
             + (float)$this->paye_tranche2
             + (float)$this->paye_tranche3
             + (float)$this->paye_transport;
    }

    public function solde(): float { return $this->totalDu() - $this->totalPaye(); }

    public function statutTranche(string $tranche): string
    {
        $du   = (float)$this->{"montant_{$tranche}"};
        $paye = (float)$this->{"paye_{$tranche}"};
        if ($du <= 0)      return 'na';
        if ($paye >= $du)  return 'paye';
        if ($paye > 0)     return 'partiel';
        return 'non_paye';
    }
}