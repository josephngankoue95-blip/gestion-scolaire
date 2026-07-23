<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompteGenere extends Model
{
    protected $table = 'comptes_generes';
    protected $fillable = [
        'user_id','nom','email','mot_de_passe','role','eleve_lie','exporte','exporte_le',
    ];
    protected $casts = ['exporte_le' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}