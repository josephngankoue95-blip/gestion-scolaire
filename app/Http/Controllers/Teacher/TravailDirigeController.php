<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TravailDirige;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravailDirigeController extends Controller
{
    public function index()
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee = AnneeScolaire::getActive();

        $tds = TravailDirige::where('enseignant_id', $enseignant->id)
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere','classe.section')
            ->latest()
            ->paginate(15);

        return view('teacher.td.index', compact('tds'));
    }

    public function create()
    {
        $enseignant   = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $annee        = AnneeScolaire::getActive();
        $affectations = $enseignant->affectations()
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere','classe.section')
            ->get();

        return view('teacher.td.create', compact('affectations'));
    }

    public function store(Request $request)
    {
        $enseignant = Auth::user()->enseignant;
        abort_if(!$enseignant, 403);

        $validated = $request->validate([
            'matiere_id'         => 'required|exists:matieres,id',
            'classe_id'          => 'required|exists:classes,id',
            'titre'              => 'required|string|max:200',
            'description'        => 'nullable|string|max:500',
            'contenu'            => 'required|string',
            'fichier'            => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'date_publication'   => 'required|date',
            'date_limite_acces'  => 'required|date|after:date_publication',
            'publie'             => 'nullable|boolean',
        ]);

        // Vérifier l'affectation
        $affectation = $enseignant->affectations()
            ->where('matiere_id', $validated['matiere_id'])
            ->where('classe_id', $validated['classe_id'])
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->exists();

        abort_if(!$affectation, 403, 'Vous n\'êtes pas affecté à cette matière/classe.');

        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('td', 'public');
        }

        $validated['enseignant_id']    = $enseignant->id;
        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;
        $validated['publie']            = $request->boolean('publie', false);

        TravailDirige::create($validated);

        return redirect()->route('teacher.td.index')
            ->with('success', 'Travail dirigé créé.');
    }

    public function show(TravailDirige $travailDirige)
    {
        $enseignant = Auth::user()->enseignant;
        abort_if($travailDirige->enseignant_id !== $enseignant?->id, 403);
        return view('teacher.td.show', compact('travailDirige'));
    }

    public function edit(TravailDirige $travailDirige)
    {
        $enseignant = $this->verifierAutorisation($travailDirige);

        $annee        = AnneeScolaire::getActive();
        $affectations = $enseignant->affectations()
            ->where('annee_scolaire_id', $annee?->id)
            ->with('matiere','classe.section')
            ->get();

        return view('teacher.td.edit', compact('travailDirige','affectations'));
    }

    public function update(Request $request, TravailDirige $travailDirige)
{
    $enseignant = $this->verifierAutorisation($travailDirige);

    $validated = $request->validate([
        'titre'              => 'required|string|max:200',
        'description'        => 'nullable|string|max:500',
        'contenu'            => 'required|string',
        'fichier'            => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        'date_publication'   => 'required|date',
        'date_limite_acces'  => 'required|date|after:date_publication',
        'publie'             => 'nullable|boolean',
    ]);

    // Gérer le fichier (si nouveau fichier uploadé)
    if ($request->hasFile('fichier')) {
        // Supprimer l'ancien fichier si présent
        if ($travailDirige->fichier) {
            Storage::disk('public')->delete($travailDirige->fichier);
        }
        $validated['fichier'] = $request->file('fichier')->store('td', 'public');
    }

    // Forcer le boolean et mettre à jour
    $validated['publie'] = $request->boolean('publie', false);

    // Mettre à jour les champs autorisés
    $travailDirige->update($validated);

    return redirect()->route('teacher.td.index')->with('success', 'TD mis à jour.');
}

    public function destroy(TravailDirige $travailDirige)
    {
        $enseignant = Auth::user()->enseignant;
        abort_if($travailDirige->enseignant_id !== $enseignant?->id, 403);

        if ($travailDirige->fichier) Storage::disk('public')->delete($travailDirige->fichier);
        $travailDirige->delete();

        return redirect()->route('teacher.td.index')->with('success', 'TD supprimé.');
    }

    /** Les élèves accèdent aux TD via cette méthode (côté parent aussi) */
    public function consulter(TravailDirige $travailDirige)
    {
        abort_if(!$travailDirige->estAccessible(), 403, 'Ce TD n\'est plus accessible.');
        return view('teacher.td.consulter', compact('travailDirige'));
    }

    private function verifierAutorisation(TravailDirige $travailDirige)
{
    $enseignant = Auth::user()->enseignant;

    abort_if(!$enseignant, 403);

    $autorise = $enseignant->affectations()
        ->where('matiere_id', $travailDirige->matiere_id)
        ->where('classe_id', $travailDirige->classe_id)
        ->where('annee_scolaire_id', $travailDirige->annee_scolaire_id)
        ->exists();

    abort_if(!$autorise, 403);

    return $enseignant;
}
}