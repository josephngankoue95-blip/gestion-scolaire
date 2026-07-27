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

    // app/Http/Controllers/Teacher/EpreuveCompositionController.php — mise à jour

public function create()
{
    $enseignant   = Auth::user()->enseignant;
    $annee        = AnneeScolaire::getActive();
    $affectations = $enseignant->affectations()->where('annee_scolaire_id', $annee?->id)->with('matiere','classe.section','classe.niveau')->get();
    $sequences    = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))->with('trimestre')->orderBy('numero')->get();
    $niveaux      = \App\Models\Niveau::orderBy('ordre')->get();

    return view('teacher.epreuves.create', compact('affectations', 'sequences', 'niveaux'));
}

public function store(Request $request)
{
    $enseignant = Auth::user()->enseignant;

    $validated = $request->validate([
        'matiere_id'      => 'required|exists:matieres,id',
        'classe_id'       => 'nullable|exists:classes,id',
        'niveau_id'       => 'required|exists:niveaux,id',
        'sequence_id'     => 'nullable|exists:sequences,id',
        'annee_examen'    => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
        'titre'           => 'required|string|max:200',
        'fichier'         => 'required|file|mimes:pdf,doc,docx|max:10240',
        'fichier_corrige' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        'archive'         => 'nullable|boolean',
    ]);

    // Vérification affectation UNIQUEMENT si c'est une épreuve de l'année en cours (pas une archive libre)
    if (!$request->boolean('archive') && $validated['classe_id']) {
        $ok = $enseignant->affectations()
            ->where('matiere_id', $validated['matiere_id'])
            ->where('classe_id', $validated['classe_id'])
            ->exists();
        abort_if(!$ok, 403, "Vous n'êtes pas affecté à cette matière/classe.");
    }

    $validated['fichier'] = $request->file('fichier')->store('epreuves', 'public');
    if ($request->hasFile('fichier_corrige')) {
        $validated['fichier_corrige'] = $request->file('fichier_corrige')->store('epreuves', 'public');
    }

    $validated['enseignant_id']     = $enseignant->id;
    $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;
    $validated['archive']           = $request->boolean('archive');

    EpreuveComposition::create($validated);

    return redirect()->route('teacher.epreuves.index')->with('success', 'Épreuve enregistrée.');
}

    public function destroy(EpreuveComposition $epreuve)
    {
        abort_if($epreuve->enseignant_id !== Auth::user()->enseignant?->id, 403);
        $epreuve->delete();
        return back()->with('success', 'Épreuve supprimée.');
    }
}