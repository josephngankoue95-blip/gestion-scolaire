<?php
namespace App\Services;

use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\ClasseModel;
use App\Models\Sequence;
use App\Models\Trimestre;
use App\Models\Note;
use Illuminate\Support\Collection;

class MoyenneService
{
    /* -------------------------------------------------------
     | HELPERS DE BASE
     ------------------------------------------------------- */

    public function appreciation(?float $note): string
    {
        if ($note === null) return '-';
        if ($note >= 16) return 'A+';
        if ($note >= 10) return 'A';
        if ($note >= 5)  return 'ECA';
        return 'NA';
    }

    public function mention(?float $moyenne): string
    {
        if ($moyenne === null) return '-';
        if ($moyenne >= 16) return 'EXCELLENT';
        if ($moyenne >= 14) return 'TRÈS BIEN';
        if ($moyenne >= 12) return 'BIEN';
        if ($moyenne >= 10) return 'ASSEZ BIEN';
        if ($moyenne >= 8)  return 'PASSABLE';
        if ($moyenne >= 5)  return 'INSUFFISANT';
        return 'TRÈS INSUFFISANT';
    }

    public function coefficientEffectif(ClasseModel $classe, Matiere $matiere): float
    {
        $pivot = $classe->matieres()->where('matiere_id', $matiere->id)->first();
        return $pivot ? (float) $pivot->pivot->coefficient : 1.0;
    }

    /**
     * Récupère une note depuis l'index pré-chargé
     * Index : $notes[$eleveId][$matiereId][$seqId]
     */
    protected function noteDepuisIndex(array $index, int $eleveId, int $matiereId, int $seqId): ?float
    {
        return $index[$eleveId][$matiereId][$seqId] ?? null;
    }

    /**
     * Construit un index de notes pour une classe + des séquences
     * Évite les N+1 requêtes
     */
    public function construireIndex(int $classeId, Collection $sequences): array
    {
        $notes = Note::where('classe_id', $classeId)
            ->whereIn('sequence_id', $sequences->pluck('id'))
            ->where('absent', false)
            ->whereNotNull('note')
            ->get();

        $index = [];
        foreach ($notes as $note) {
            $index[$note->eleve_id][$note->matiere_id][$note->sequence_id] = (float) $note->note;
        }
        return $index;
    }

    /* -------------------------------------------------------
     | MOYENNES PAR MATIÈRE
     ------------------------------------------------------- */

    public function moyenneEleveMatiere(int $eleveId, int $matiereId, Collection $sequences, array $index): ?float
    {
        $notes = $sequences->map(fn($s) => $this->noteDepuisIndex($index, $eleveId, $matiereId, $s->id))
            ->filter(fn($n) => $n !== null);
        return $notes->isEmpty() ? null : round($notes->avg(), 2);
    }

    public function moyenneClasseMatiere(Collection $eleves, int $matiereId, Collection $sequences, array $index): ?float
    {
        $moyennes = $eleves->map(fn($e) => $this->moyenneEleveMatiere($e->id, $matiereId, $sequences, $index))
            ->filter(fn($m) => $m !== null);
        return $moyennes->isEmpty() ? null : round($moyennes->avg(), 2);
    }

    public function rangMatiereClasse(Collection $eleves, int $matiereId, Collection $sequences, array $index, int $eleveId): string
    {
        $classement = $eleves->map(fn($e) => [
            'id'  => $e->id,
            'moy' => $this->moyenneEleveMatiere($e->id, $matiereId, $sequences, $index),
        ])->filter(fn($i) => $i['moy'] !== null)->sortByDesc('moy')->values();

        $rang = $classement->search(fn($i) => $i['id'] === $eleveId);
        return $rang !== false ? ($rang + 1) . 'e' : '-';
    }

    public function nbElevesSup(Collection $eleves, int $matiereId, Collection $sequences, array $index, float $seuil = 10): int
    {
        return $eleves->filter(fn($e) => ($this->moyenneEleveMatiere($e->id, $matiereId, $sequences, $index) ?? -1) >= $seuil)->count();
    }

    /* -------------------------------------------------------
     | BULLETIN COMPLET (TRIMESTRE)
     ------------------------------------------------------- */

    public function construireBulletinTrimestre(Eleve $eleve, ClasseModel $classe, Trimestre $trimestre): array
    {
        $trimestre->loadMissing('sequences');
        $sequences   = $trimestre->sequences->sortBy('numero');
        $seq1        = $sequences->get(0);
        $seq2        = $sequences->get(1);
        $anneeId     = $trimestre->annee_scolaire_id;

        // Tous les élèves de la classe
        $tousEleves = Eleve::where('classe_id', $classe->id)
            ->get();

        // Index notes
        $index = $this->construireIndex($classe->id, $sequences);

        // Affectations (enseignants par matière)
        $affectations = \App\Models\Affectation::where('classe_id', $classe->id)
            ->where('annee_scolaire_id', $anneeId)
            ->with('enseignant.user')
            ->get()->keyBy('matiere_id');

        // Matières de la classe triées
        $matieres = $classe->matieres()
            ->orderBy('classe_matiere.groupe')
            ->orderBy('classe_matiere.ordre')
            ->get();

        $totalPoints = 0;
        $totalCoef   = 0;
        $details     = [];
        $groupes     = [];

        foreach ($matieres as $matiere) {
            $coef   = (float) $matiere->pivot->coefficient;
            $groupe = (int)   $matiere->pivot->groupe;

            $note1 = $seq1 ? ($this->noteDepuisIndex($index, $eleve->id, $matiere->id, $seq1->id)) : null;
            $note2 = $seq2 ? ($this->noteDepuisIndex($index, $eleve->id, $matiere->id, $seq2->id)) : null;

            $notes   = collect([$note1, $note2])->filter(fn($n) => $n !== null);
            $moyenne = $notes->isEmpty() ? null : round($notes->avg(), 2);

            $moyClasse = $this->moyenneClasseMatiere($tousEleves, $matiere->id, $sequences, $index);
            $rang      = $this->rangMatiereClasse($tousEleves, $matiere->id, $sequences, $index, $eleve->id);
            $nb10      = $this->nbElevesSup($tousEleves, $matiere->id, $sequences, $index, 10);

            if ($moyenne !== null) {
                $totalPoints += $moyenne * $coef;
                $totalCoef   += $coef;
            }

            // Init groupe
            if (!isset($groupes[$groupe])) {
                $groupes[$groupe] = ['total_points' => 0, 'total_coef' => 0, 'moyenne' => null];
            }
            if ($moyenne !== null) {
                $groupes[$groupe]['total_points'] += $moyenne * $coef;
                $groupes[$groupe]['total_coef']   += $coef;
            }

            $details[] = [
                'matiere'      => $matiere->nom,
                'enseignant'   => $affectations->get($matiere->id)?->enseignant?->user?->name ?? '',
                'groupe'       => $groupe,
                'coefficient'  => $coef,
                'note_seq1'    => $note1,
                'note_seq2'    => $note2,
                'moyenne'      => $moyenne,
                'moy_coef'     => $moyenne !== null ? round($moyenne * $coef, 2) : null,
                'rang'         => $rang,
                'moy_classe'   => $moyClasse,
                'nb_sup10'     => $nb10,
                'appreciation' => $this->appreciation($moyenne),
            ];
        }

        // Calcul moyennes par groupe
        foreach ($groupes as $g => $data) {
            $groupes[$g]['moyenne'] = $data['total_coef'] > 0
                ? round($data['total_points'] / $data['total_coef'], 2)
                : null;
        }
        ksort($groupes);

        $moyenneGenerale = $totalCoef > 0 ? round($totalPoints / $totalCoef, 2) : null;
        $mention         = $this->mention($moyenneGenerale);

        // Classement global pour le rang
        $classementGlobal = $this->rangTrimestre($classe, $trimestre);
        $rang             = collect($classementGlobal)->firstWhere('eleve.id', $eleve->id)['rang'] ?? '-';
        $effectif         = $tousEleves->count();

        // Profil de la classe
        $moyennesGlobales = collect($classementGlobal)->pluck('moyenne')->filter();
        $moyenneClasse    = $moyennesGlobales->isEmpty() ? null : round($moyennesGlobales->avg(), 2);
        $moyPremier       = $moyennesGlobales->max();
        $moyDernier       = $moyennesGlobales->min();
        $nbSup10Global    = $moyennesGlobales->filter(fn($m) => $m >= 10)->count();
        $tauxReussite     = $effectif > 0 ? round(($nbSup10Global / $effectif) * 100, 2) : 0;

        // Absences
        $absences = \App\Models\Absence::where('eleve_id', $eleve->id)
            ->where('classe_id', $classe->id)
            ->whereBetween('date_absence', [
                $trimestre->date_debut ?? now()->startOfYear(),
                $trimestre->date_fin   ?? now()->endOfYear(),
            ])->get();

        return [
            'details'          => $details,
            'groupes'          => $groupes,
            'total_points'     => round($totalPoints, 2),
            'total_coef'       => $totalCoef,
            'moyenne_generale' => $moyenneGenerale,
            'rang'             => $rang,
            'effectif'         => $effectif,
            'mention'          => $mention,
            'seq1'             => $seq1,
            'seq2'             => $seq2,
            'profil_classe'    => [
                'moyenne'       => $moyenneClasse,
                'nb_sup10'      => $nbSup10Global,
                'taux_reussite' => $tauxReussite,
                'moy_premier'   => $moyPremier,
                'moy_dernier'   => $moyDernier,
            ],
            'absences_total' => $absences->count(),
            'absences_nj'    => $absences->where('justifiee', false)->count(),
        ];
    }

    /**
     * Bulletin séquentiel
     */
    public function construireBulletinSequence(Eleve $eleve, ClasseModel $classe, Sequence $sequence): array
    {
        $sequences = collect([$sequence]);
        $anneeId   = $sequence->trimestre->annee_scolaire_id;

        $tousEleves = Eleve::where('classe_id', $classe->id)
            ->get();

        $index        = $this->construireIndex($classe->id, $sequences);
        $affectations = \App\Models\Affectation::where('classe_id', $classe->id)
            ->where('annee_scolaire_id', $anneeId)
            ->with('enseignant.user')->get()->keyBy('matiere_id');

        $matieres    = $classe->matieres()->orderBy('classe_matiere.groupe')->orderBy('classe_matiere.ordre')->get();
        $totalPoints = 0;
        $totalCoef   = 0;
        $details     = [];
        $groupes     = [];

        foreach ($matieres as $matiere) {
            $coef   = (float) $matiere->pivot->coefficient;
            $groupe = (int)   $matiere->pivot->groupe;
            $note   = $this->noteDepuisIndex($index, $eleve->id, $matiere->id, $sequence->id);

            $moyClasse = $this->moyenneClasseMatiere($tousEleves, $matiere->id, $sequences, $index);
            $rang      = $this->rangMatiereClasse($tousEleves, $matiere->id, $sequences, $index, $eleve->id);
            $nb10      = $this->nbElevesSup($tousEleves, $matiere->id, $sequences, $index, 10);

            if ($note !== null) {
                $totalPoints += $note * $coef;
                $totalCoef   += $coef;
            }

            if (!isset($groupes[$groupe])) $groupes[$groupe] = ['total_points' => 0, 'total_coef' => 0, 'moyenne' => null];
            if ($note !== null) {
                $groupes[$groupe]['total_points'] += $note * $coef;
                $groupes[$groupe]['total_coef']   += $coef;
            }

            $details[] = [
                'matiere'     => $matiere->nom,
                'enseignant'  => $affectations->get($matiere->id)?->enseignant?->user?->name ?? '',
                'groupe'      => $groupe,
                'coefficient' => $coef,
                'note'        => $note,
                'moy_coef'    => $note !== null ? round($note * $coef, 2) : null,
                'rang'        => $rang,
                'moy_classe'  => $moyClasse,
                'nb_sup10'    => $nb10,
                'appreciation'=> $this->appreciation($note),
            ];
        }

        foreach ($groupes as $g => $data) {
            $groupes[$g]['moyenne'] = $data['total_coef'] > 0 ? round($data['total_points'] / $data['total_coef'], 2) : null;
        }
        ksort($groupes);

        $moyenneGenerale = $totalCoef > 0 ? round($totalPoints / $totalCoef, 2) : null;
        $classementGlobal = $this->rangSequence($classe, $sequence);
        $rang             = collect($classementGlobal)->firstWhere('eleve.id', $eleve->id)['rang'] ?? '-';
        $effectif         = $tousEleves->count();
        $moyennesGlobales = collect($classementGlobal)->pluck('moyenne')->filter();

        return [
            'details'          => $details,
            'groupes'          => $groupes,
            'total_points'     => round($totalPoints, 2),
            'total_coef'       => $totalCoef,
            'moyenne_generale' => $moyenneGenerale,
            'rang'             => $rang,
            'effectif'         => $effectif,
            'mention'          => $this->mention($moyenneGenerale),
            'profil_classe'    => [
                'moyenne'       => $moyennesGlobales->isEmpty() ? null : round($moyennesGlobales->avg(), 2),
                'nb_sup10'      => $moyennesGlobales->filter(fn($m) => $m >= 10)->count(),
                'taux_reussite' => $effectif > 0 ? round(($moyennesGlobales->filter(fn($m) => $m >= 10)->count() / $effectif) * 100, 2) : 0,
                'moy_premier'   => $moyennesGlobales->max(),
                'moy_dernier'   => $moyennesGlobales->min(),
            ],
            'absences_total' => 0,
            'absences_nj'    => 0,
        ];
    }

    /* -------------------------------------------------------
     | RANGS (inchangés)
     ------------------------------------------------------- */

    public function rangSequence(ClasseModel $classe, Sequence $sequence): array
    {
        $anneeId  = $sequence->trimestre->annee_scolaire_id;
        $eleves = Eleve::where('classe_id', $classe->id)
    ->get();
        $sequences = collect([$sequence]);
        $index     = $this->construireIndex($classe->id, $sequences);

        return $this->_calculerRangDepuisIndex($eleves, $classe, $sequences, $index);
    }

    public function rangTrimestre(ClasseModel $classe, Trimestre $trimestre): array
    {
        $trimestre->loadMissing('sequences');
        $sequences = $trimestre->sequences->sortBy('numero');
        $eleves = Eleve::where('classe_id', $classe->id)
    ->get();
        $index     = $this->construireIndex($classe->id, $sequences);

        return $this->_calculerRangDepuisIndex($eleves, $classe, $sequences, $index);
    }

    protected function _calculerRangDepuisIndex(Collection $eleves, ClasseModel $classe, Collection $sequences, array $index): array
    {
        $matieres = $classe->matieres()->get();

        $classement = $eleves->map(function ($eleve) use ($matieres, $sequences, $index, $classe) {
            $totalP = 0; $totalC = 0;
            foreach ($matieres as $matiere) {
                $coef  = (float) $matiere->pivot->coefficient;
                $notes = $sequences->map(fn($s) => $this->noteDepuisIndex($index, $eleve->id, $matiere->id, $s->id))->filter(fn($n) => $n !== null);
                if ($notes->isEmpty()) continue;
                $moy    = $notes->avg();
                $totalP += $moy * $coef;
                $totalC += $coef;
            }
            return [
                'eleve'   => $eleve,
                'moyenne' => $totalC > 0 ? round($totalP / $totalC, 2) : null,
            ];
        })
        ->filter(fn($i) => $i['moyenne'] !== null)
        ->sortByDesc('moyenne')->values()
        ->map(fn($i, $k) => [...$i, 'rang' => $k + 1]);

        return $classement->toArray();
    }

    public function bulletinAnnuel(Eleve $eleve, ClasseModel $classe, array $trimestres): array
{
    $matieres    = $classe->matieres()->orderBy('classe_matiere.groupe')->orderBy('classe_matiere.ordre')->get();
    $totalPoints = 0;
    $totalCoef   = 0;
    $details     = [];
    $groupes     = [];

    foreach ($matieres as $matiere) {
        $coef   = $this->coefficientEffectif($classe, $matiere);
        $groupe = (int) $matiere->pivot->groupe;

        // Moyennes par trimestre
        $moysParTrim = [];
        foreach ($trimestres as $i => $trim) {
            $moysParTrim['moy_t'.($i+1)] = $this->moyenneTrimestre($eleve, $matiere, $trim, $classe);
        }

        $moysValides = collect($moysParTrim)->filter(fn($m) => $m !== null);
        $moyenne     = $moysValides->isEmpty() ? null : round($moysValides->avg(), 2);

        if ($moyenne !== null) {
            $totalPoints += $moyenne * $coef;
            $totalCoef   += $coef;
        }

        if (!isset($groupes[$groupe])) {
            $groupes[$groupe] = ['total_points' => 0, 'total_coef' => 0, 'moyenne' => null];
        }
        if ($moyenne !== null) {
            $groupes[$groupe]['total_points'] += $moyenne * $coef;
            $groupes[$groupe]['total_coef']   += $coef;
        }

        $details[] = array_merge([
            'matiere'      => $matiere->nom,
            'enseignant'   => '',
            'groupe'       => $groupe,
            'coefficient'  => $coef,
            'moyenne'      => $moyenne,
            'points'       => $moyenne !== null ? round($moyenne * $coef, 2) : null,
            'appreciation' => $this->appreciation($moyenne),
        ], $moysParTrim); // ← moy_t1, moy_t2, moy_t3
    }

    foreach ($groupes as $g => $data) {
        $groupes[$g]['moyenne'] = $data['total_coef'] > 0
            ? round($data['total_points'] / $data['total_coef'], 2)
            : null;
    }
    ksort($groupes);

    $moyenneGenerale = $totalCoef > 0 ? round($totalPoints / $totalCoef, 2) : null;
    $effectif = Eleve::where('classe_id', $classe->id)->count();

    return [
        'details'          => $details,
        'groupes'          => $groupes,
        'total_points'     => round($totalPoints, 2),
        'total_coef'       => $totalCoef,
        'moyenne_generale' => $moyenneGenerale,
        'rang'             => '-',
        'effectif'         => $effectif,
        'mention'          => $this->mention($moyenneGenerale),
        'profil_classe'    => ['moyenne' => null, 'nb_sup10' => 0, 'taux_reussite' => 0, 'moy_premier' => null, 'moy_dernier' => null],
        'absences_total'   => 0,
        'absences_nj'      => 0,
    ];
}

/**
 * Calcule la moyenne d'un élève dans une matière pour un trimestre
 */
public function moyenneTrimestre(Eleve $eleve, Matiere $matiere, Trimestre $trimestre, ClasseModel $classe): ?float
{
    $trimestre->loadMissing('sequences');

    $sequences = $trimestre->sequences;

    $notes = Note::where('eleve_id', $eleve->id)
        ->where('classe_id', $classe->id)
        ->where('matiere_id', $matiere->id)
        ->whereIn('sequence_id', $sequences->pluck('id'))
        ->where('absent', false)
        ->whereNotNull('note')
        ->pluck('note');

    if ($notes->isEmpty()) {
        return null;
    }

    return round($notes->avg(), 2);
}

}