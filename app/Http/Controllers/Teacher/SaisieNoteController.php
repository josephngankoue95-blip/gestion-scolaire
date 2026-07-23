<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Affectation;
use App\Models\Section;
use App\Models\ClasseModel;
use App\Models\Matiere;
use App\Models\Sequence;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaisieNoteController extends Controller
{
    public function dashboard()
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee = AnneeScolaire::getActive();

        $affectations = $enseignant->affectations()
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere', 'classe.section')
            ->get();

        $classesCount  = $affectations->pluck('classe_id')->unique()->count();
        $matieresCount = $affectations->pluck('matiere_id')->unique()->count();

        return view('teacher.dashboard', compact(
            'enseignant',
            'affectations',
            'classesCount',
            'matieresCount'
        ));
    }

    public function index()
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee = AnneeScolaire::getActive();

        $sections = Section::whereHas('classes', function ($q) use ($enseignant, $annee) {
            $q->whereHas('affectations', function ($q2) use ($enseignant, $annee) {
                $q2->where('enseignant_id', $enseignant->id)
                   ->where('annee_scolaire_id', $annee?->id);
            });
        })->get();

        $sequences = Sequence::whereHas('trimestre', function ($q) use ($annee) {
            $q->where('annee_scolaire_id', $annee?->id);
        })
        ->with('trimestre')
        ->orderBy('numero')
        ->get();

        return view('teacher.saisie.index', compact('sections', 'sequences'));
    }

    public function classesBySection(Request $request)
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee = AnneeScolaire::getActive();

        $classes = ClasseModel::where('section_id', $request->section_id)
            ->where('annee_scolaire_id', $annee?->id)
            ->whereHas('affectations', function ($q) use ($enseignant, $annee) {
                $q->where('enseignant_id', $enseignant->id)
                  ->where('annee_scolaire_id', $annee?->id);
            })
            ->get(['id', 'nom']);

        return response()->json($classes);
    }

    public function matieresByClasse(Request $request)
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee = AnneeScolaire::getActive();

        $matiereIds = Affectation::where('enseignant_id', $enseignant->id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_scolaire_id', $annee?->id)
            ->pluck('matiere_id');

        $classe = ClasseModel::findOrFail($request->classe_id);

        $matieres = $classe->matieres()
            ->whereIn('matieres.id', $matiereIds)
            ->get()
            ->map(function ($matiere) {
                return [
                    'id'          => $matiere->id,
                    'nom'         => $matiere->nom,
                    'coefficient' => (float) $matiere->pivot->coefficient,
                ];
            });

        return response()->json($matieres);
    }

    public function saisir(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'sequence_id' => 'required|exists:sequences,id',
        ]);

        $enseignant = Auth::user()->enseignant;
        $annee      = AnneeScolaire::getActive();

        abort_if(!$enseignant, 403);

        $affectationValide = $enseignant->affectations()
            ->where('matiere_id', $request->matiere_id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_scolaire_id', $annee?->id)
            ->exists();

        abort_if(!$affectationValide, 403, 'Non autorisé');

        $classe   = ClasseModel::with('section')->findOrFail($request->classe_id);
        $matiere  = Matiere::findOrFail($request->matiere_id);
        $sequence = Sequence::with('trimestre')->findOrFail($request->sequence_id);

        $pivot       = $classe->matieres()->where('matiere_id', $matiere->id)->first();
        $coefficient = $pivot ? (float) $pivot->pivot->coefficient : 1;

        // ✅ CHANGEMENT IMPORTANT ICI
        $eleves = $classe->eleves()
            ->orderBy('nom')
            ->get();

        $notesExistantes = Note::where('matiere_id', $matiere->id)
            ->where('classe_id', $classe->id)
            ->where('sequence_id', $sequence->id)
            ->get()
            ->keyBy('eleve_id');

        return view('teacher.saisie.form', compact(
            'classe',
            'matiere',
            'sequence',
            'coefficient',
            'eleves',
            'notesExistantes',
            'enseignant'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'sequence_id' => 'required|exists:sequences,id',
            'notes'       => 'required|array',
        ]);

        $enseignant = Auth::user()->enseignant;
        $annee      = AnneeScolaire::getActive();

        abort_if(!$enseignant, 403);

        abort_if(!$enseignant->affectations()
            ->where('matiere_id', $request->matiere_id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_scolaire_id', $annee?->id)
            ->exists(), 403);

        DB::transaction(function () use ($request, $enseignant) {

            foreach ($request->notes as $item) {

                Note::updateOrCreate(
                    [
                        'eleve_id'    => $item['eleve_id'],
                        'matiere_id'  => $request->matiere_id,
                        'classe_id'   => $request->classe_id,
                        'sequence_id' => $request->sequence_id,
                    ],
                    [
                        'note'          => $item['absent'] ?? false ? null : ($item['note'] ?? null),
                        'absent'        => $item['absent'] ?? false,
                        'enseignant_id' => $enseignant->id,
                    ]
                );
            }
        });

        return redirect()->route('teacher.saisie.index')
            ->with('success', 'Notes enregistrées avec succès.');
    }
}