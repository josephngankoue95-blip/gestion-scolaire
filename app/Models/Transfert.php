<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfert extends Model
{
    protected $fillable = [
        'eleve_id','classe_source_id','classe_destination_id',
        'annee_scolaire_id','date_transfert','motif','effectue_par',
    ];
    protected $casts = ['date_transfert' => 'date'];

    public function eleve(): BelongsTo { return $this->belongsTo(Eleve::class); }
    public function classeSource(): BelongsTo { return $this->belongsTo(ClasseModel::class, 'classe_source_id'); }
    public function classeDestination(): BelongsTo { return $this->belongsTo(ClasseModel::class, 'classe_destination_id'); }
    public function effectuePar(): BelongsTo { return $this->belongsTo(User::class, 'effectue_par'); }
}