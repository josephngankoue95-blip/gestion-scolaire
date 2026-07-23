<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Sequence;
use App\Models\AnneeScolaire;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReleveNoteController extends Controller
{
    public function index()
    {
        $annee     = AnneeScolaire::getActive();
        $classes   = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('niveau_id')->get();
        $enseignants = Enseignant::with('user')->where('statut','actif')->get();
        $sequences = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))->with('trimestre')->orderBy('numero')->get();

        return view('admin.releves.index', compact('classes', 'enseignants', 'sequences'));
    }

    /** Relevé par classe (toutes les matières) */
   public function parClasse(Request $request)
{
    $request->validate([
        'classe_id'   => 'required|exists:classes,id',
        'sequence_id' => 'required|exists:sequences,id',
    ]);

    $classe = ClasseModel::with([
        'section',
        'anneeScolaire',
        'matieres',
        'eleves' // ✅ ICI
    ])->findOrFail($request->classe_id);

    $sequence = Sequence::with('trimestre')->findOrFail($request->sequence_id);

    // ✅ LES ÉLÈVES VIENNENT DIRECTEMENT DE LA CLASSE
    $eleves = $classe->eleves->sortBy('nom');

    $matieres = $classe->matieres()
        ->orderBy('classe_matiere.groupe')
        ->orderBy('classe_matiere.ordre')
        ->get();

    $pdf = Pdf::loadView('admin.releves.pdf-classe', [
        'classe'        => $classe,
        'sequence'      => $sequence,
        'eleves'        => $eleves,
        'matieres'      => $matieres,
        'etablissement' => Etablissement::instance(),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream("releve_classe_{$classe->nom}_{$sequence->nom}.pdf");
}

    /** Relevé par enseignant (ses matières/classes) */
    public function parEnseignant(Request $request)
    {
        $request->validate([
            'enseignant_id' => 'required|exists:enseignants,id',
            'sequence_id'   => 'required|exists:sequences,id',
        ]);

        $annee      = AnneeScolaire::getActive();
        $enseignant = Enseignant::with('user')->findOrFail($request->enseignant_id);
        $sequence   = Sequence::with('trimestre')->findOrFail($request->sequence_id);

        $affectations = $enseignant->affectations()
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere', 'classe.eleves', 'classe.section')
            ->get();

        $pdf = Pdf::loadView('admin.releves.pdf-enseignant', [
            'enseignant'    => $enseignant,
            'sequence'      => $sequence,
            'affectations'  => $affectations,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("releve_{$enseignant->user->name}_{$sequence->nom}.pdf");
    }
}