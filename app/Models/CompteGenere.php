<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompteGenere extends Model
{
    /**
     * Nom réel de la table dans la base de données.
     */
    protected $table = 'comptes_generes';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'eleve_lie',
        'user_id',
        'envoye_le',
    ];

protected $casts = [
    'envoye_le' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}