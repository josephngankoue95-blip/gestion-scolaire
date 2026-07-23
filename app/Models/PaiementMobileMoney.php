<?php
// app/Models/PaiementMobileMoney.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementMobileMoney extends Model
{
    protected $table = 'paiements_mobile_money';
    protected $fillable = [
        'scolarite_id','operateur','numero_telephone','type_paiement','montant',
        'reference_transaction','statut','initie_par','verifie_par','verifie_le','note',
    ];
    protected $casts = ['verifie_le' => 'datetime'];

    public function scolarite(): BelongsTo { return $this->belongsTo(Scolarite::class); }
    public function initiePar(): BelongsTo { return $this->belongsTo(User::class, 'initie_par'); }
    public function verifiePar(): BelongsTo { return $this->belongsTo(User::class, 'verifie_par'); }

    public function badgeClass(): string
    {
        return match($this->statut) {
            'confirme' => 'badge-green',
            'echoue', 'annule' => 'badge-red',
            default => 'badge-amber',
        };
    }
}