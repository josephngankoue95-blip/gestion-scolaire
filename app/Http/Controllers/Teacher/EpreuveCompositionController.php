<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\EpreuveComposition;
use App\Models\AnneeScolaire;
use App\Models\Sequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpreuveCompositionController extends Controller
{
    public function index()
    {
        $enseignant = Auth::user()->enseignant;
        $annee = AnneeScolaire::getActive();

        $epreuves = EpreuveComposition::where('enseignant_id', $enseignant->id)
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere', 'classe', 'sequence.trimestre')
            ->latest()->paginate(15);

        return view('teacher.epreuves.index', compact('epreuves'));
    }

    public function create()
    {
        $enseignant   = Auth::user()->enseignant;
        $annee        = AnneeScolaire::getActive();
        $affectations = $enseignant->affectations()->where('annee_scolaire_id', $annee?->id)->with('matiere','classe.section')->get();
        $sequences    = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))->with('trimestre')->orderBy('numero')->get();

        return view('teacher.epreuves.create', compact('affectations', 'sequences'));
    }

    public function store(Request $request)
    {
        $enseignant = Auth::user()->enseignant;

        $validated = $request->validate([
            'matiere_id'  => 'required|exists:matieres,id',
            'classe_id'   => 'required|exists:classes,id',
            'sequence_id' => 'required|exists:sequences,id',
            'titre'       => 'required|string|max:200',
            'fichier'     => 'required|file|mimes:pdf,doc,docx|max:10240',
            'fichier_corrige' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Vérifier l'affectation
        $ok = $enseignant->affectations()
            ->where('matiere_id', $validated['matiere_id'])
            ->where('classe_id', $validated['classe_id'])
            ->exists();
        abort_if(!$ok, 403, "Vous n'êtes pas affecté à cette matière/classe.");

        $validated['fichier'] = $request->file('fichier')->store('epreuves', 'public');
        if ($request->hasFile('fichier_corrige')) {
            $validated['fichier_corrige'] = $request->file('fichier_corrige')->store('epreuves', 'public');
        }

        $validated['enseignant_id']     = $enseignant->id;
        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;

        EpreuveComposition::create($validated);

        return redirect()->route('teacher.epreuves.index')->with('success', 'Épreuve envoyée.');
    }

    public function destroy(EpreuveComposition $epreuve)
    {
        abort_if($epreuve->enseignant_id !== Auth::user()->enseignant?->id, 403);
        $epreuve->delete();
        return back()->with('success', 'Épreuve supprimée.');
    }
}