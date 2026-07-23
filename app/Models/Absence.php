<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    protected $fillable = [
        'eleve_id', 'classe_id', 'emploi_temps_id', 'date_absence',
        'type', 'justifiee', 'motif', 'signale_par',
    ];

    protected $casts = [
        'date_absence' => 'date',
        'justifiee' => 'boolean',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(ClasseModel::class, 'classe_id');
    }

    public function emploiTemps(): BelongsTo
    {
        return $this->belongsTo(EmploiTemps::class, 'emploi_temps_id');
    }

    public function signalePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signale_par');
    }
}