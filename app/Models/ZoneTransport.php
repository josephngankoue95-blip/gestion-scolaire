<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZoneTransport extends Model
{
    protected $table = 'zones_transport';
    protected $fillable = ['annee_scolaire_id','nom','quartiers','montant','actif'];

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    public function scolarites(): HasMany
    {
        return $this->hasMany(Scolarite::class, 'zone_transport_id');
    }
}