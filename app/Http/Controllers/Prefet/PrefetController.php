<?php
namespace App\Http\Controllers\Prefet;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Enseignant;
use App\Models\Sequence;
use App\Models\Trimestre;
use App\Models\Note;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class PrefetController extends Controller
{
    public function dashboard(Request $request)
{
    $annee = AnneeScolaire::getActive();

    $totalClasses = ClasseModel::where('annee_scolaire_id', $annee?->id)->count();

    $sequences = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
        ->with('trimestre')
        ->orderBy('numero')
        ->get();

    // Séquence sélectionnée : celle demandée, sinon la plus récente
    $sequenceId = $request->filled('sequence_id')
        ? $request->sequence_id
        : $sequences->last()?->id;

    $sequenceSelectionnee = $sequences->firstWhere('id', $sequenceId);

    $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
        ->with('section', 'matieres')
        ->orderBy('nom')
        ->get();

    $suiviSaisies = [];

    if ($sequenceSelectionnee) {
        foreach ($classes as $classe) {
            $effectif = $classe->eleves()->count();

            foreach ($classe->matieres as $matiere) {
                $nbNotes = Note::where('classe_id', $classe->id)
                    ->where('matiere_id', $matiere->id)
                    ->where('sequence_id', $sequenceSelectionnee->id)
                    ->count();

                $suiviSaisies[] = [
                    'classe_id'   => $classe->id,
                    'classe'      => $classe->nom,
                    'matiere_id'  => $matiere->id,
                    'matiere'     => $matiere->nom,
                    'effectif'    => $effectif,
                    'saisies'     => $nbNotes,
                    'complet'     => $effectif > 0 && $nbNotes >= $effectif,
                ];
            }
        }
    }

    $incompletes = collect($suiviSaisies)->where('complet', false)->count();

    return view('prefet.dashboard', compact(
        'annee', 'totalClasses', 'sequences', 'sequenceSelectionnee', 'suiviSaisies', 'incompletes'
    ));
}

    /** Formulaire de sélection pour saisir/contrôler les notes de n'importe quelle classe */
    public function saisieIndex()
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('nom')->get();
        $sequences = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
            ->with('trimestre')->orderBy('numero')->get();

        return view('prefet.saisie.index', compact('classes', 'sequences'));
    }

    public function ajaxMatieres(Request $request)
    {
        $classe = ClasseModel::with('matieres')->findOrFail($request->classe_id);
        return response()->json($classe->matieres->map(fn($m) => [
            'id' => $m->id, 'nom' => $m->nom, 'coefficient' => (float)$m->pivot->coefficient,
        ]));
    }

    /** Le préfet peut saisir les notes de n'importe quelle classe/matière */
    public function saisieForm(Request $request)
    {
        $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'sequence_id' => 'required|exists:sequences,id',
        ]);

        $classe   = ClasseModel::with('section')->findOrFail($request->classe_id);
        $matiere  = \App\Models\Matiere::findOrFail($request->matiere_id);
        $sequence = Sequence::with('trimestre')->findOrFail($request->sequence_id);

        $eleves = $classe->eleves()->orderBy('nom')->get();

        $notesExistantes = Note::where('classe_id', $classe->id)
            ->where('matiere_id', $matiere->id)
            ->where('sequence_id', $sequence->id)
            ->get()->keyBy('eleve_id');

        $pivot = $classe->matieres()->where('matiere_id', $matiere->id)->first();
        $coefficient = $pivot?->pivot->coefficient ?? 1;

        return view('prefet.saisie.form', compact('classe', 'matiere', 'sequence', 'eleves', 'notesExistantes', 'coefficient'));
    }

    public function saisieStore(Request $request)
    {
        $validated = $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'sequence_id' => 'required|exists:sequences,id',
            'notes'       => 'required|array',
            'notes.*'     => 'nullable|numeric|min:0|max:20',
            'absents'     => 'nullable|array',
        ]);

        $absents = $request->input('absents', []);

        foreach ($validated['notes'] as $eleveId => $note) {
            Note::updateOrCreate(
                [
                    'eleve_id'    => $eleveId,
                    'matiere_id'  => $validated['matiere_id'],
                    'classe_id'   => $validated['classe_id'],
                    'sequence_id' => $validated['sequence_id'],
                ],
                [
                    'note'         => in_array($eleveId, $absents) ? null : $note,
                    'absent'       => in_array($eleveId, $absents),
                    'enseignant_id'=> null, // saisi par le préfet, pas un enseignant titulaire
                ]
            );
        }

        return redirect()->route('prefet.saisie.index')
            ->with('success', 'Notes enregistrées avec succès (saisie effectuée par la préfecture des études).');
    }
}