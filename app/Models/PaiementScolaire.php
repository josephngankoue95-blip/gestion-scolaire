<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementScolaire extends Model
{
    protected $table = 'paiements_scolarite';
    protected $fillable = [
        'scolarite_id','type','montant','date_paiement',
        'numero_recu','note','enregistre_par',
    ];
    protected $casts = ['date_paiement' => 'date'];

    public function scolarite(): BelongsTo { return $this->belongsTo(Scolarite::class); }
    public function enregistrePar(): BelongsTo { return $this->belongsTo(User::class, 'enregistre_par'); }

    public static function genererNumeroRecu(): string
    {
        $annee  = date('Y');
        $dernier = static::where('numero_recu', 'like', "REC{$annee}%")->count() + 1;
        return "REC{$annee}" . str_pad($dernier, 5, '0', STR_PAD_LEFT);
    }
}