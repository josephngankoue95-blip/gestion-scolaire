<?php
// app/Models/DecisionConseil.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionConseil extends Model
{
    protected $table = 'decisions_conseil';
    protected $fillable = [
        'conseil_classe_id', 'eleve_id', 'type_decision', 'motif',
        'observation', 'date_application', 'decidee_par',
    ];
    protected $casts = ['date_application' => 'date'];

    public function conseil(): BelongsTo { return $this->belongsTo(ConseilClasse::class, 'conseil_classe_id'); }
    public function eleve(): BelongsTo { return $this->belongsTo(Eleve::class); }
    public function decideePar(): BelongsTo { return $this->belongsTo(User::class, 'decidee_par'); }

    public function badgeClass(): string
    {
        return match($this->type_decision) {
            'passage', 'felicitations', 'encouragements', 'tableau_honneur' => 'badge-green',
            'redoublement', 'avertissement', 'blame' => 'badge-amber',
            'exclusion_temporaire', 'exclusion_definitive' => 'badge-red',
            default => 'badge-gray',
        };
    }

    public function libelle(): string
    {
        return match($this->type_decision) {
            'passage' => 'Passage en classe supérieure',
            'redoublement' => 'Redoublement',
            'exclusion_temporaire' => 'Exclusion temporaire',
            'exclusion_definitive' => 'Exclusion définitive',
            'avertissement' => 'Avertissement',
            'felicitations' => 'Félicitations',
            'encouragements' => 'Encouragements',
            'blame' => 'Blâme',
            'tableau_honneur' => 'Tableau d\'honneur',
            default => 'Autre',
        };
    }
}