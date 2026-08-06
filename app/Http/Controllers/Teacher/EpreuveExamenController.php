<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\EpreuveExamen;
use App\Models\Niveau;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EpreuveExamenController extends Controller
{
    public function index(Request $request)
    {
        $query = EpreuveExamen::with(['matiere', 'niveau', 'inserePar']);

        if ($request->filled('niveau_id')) $query->where('niveau_id', $request->niveau_id);
        if ($request->filled('annee_examen')) $query->where('annee_examen', $request->annee_examen);

        $epreuves = $query->latest()->paginate(15);
        $niveaux  = Niveau::orderBy('ordre')->get();

        return view('teacher.epreuves-examen.index', compact('epreuves', 'niveaux'));
    }

    public function create()
    {
        $matieres = Matiere::orderBy('nom')->get();
        $niveaux  = Niveau::orderBy('ordre')->get();

        return view('teacher.epreuves-examen.create', compact('matieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matiere_id'      => 'required|exists:matieres,id',
            'niveau_id'       => 'required|exists:niveaux,id',
            'annee_examen'    => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'titre'           => 'required|string|max:200',
            'fichier'         => 'required|file|mimes:pdf,doc,docx|max:10240',
            'fichier_corrige' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['fichier'] = $request->file('fichier')->store('epreuves-examens', 'public');
        if ($request->hasFile('fichier_corrige')) {
            $validated['fichier_corrige'] = $request->file('fichier_corrige')->store('epreuves-examens', 'public');
        }

        $validated['insere_par'] = Auth::id();

        EpreuveExamen::create($validated);

        return redirect()->route('teacher.epreuves-examen.index')->with('success', 'Épreuve d\'examen enregistrée.');
    }

    public function show(EpreuveExamen $epreuveExamen)
    {
        $epreuveExamen->load(['matiere', 'niveau', 'inserePar']);
        return view('teacher.epreuves-examen.show', compact('epreuveExamen'));
    }

    public function destroy(EpreuveExamen $epreuveExamen)
    {
        if ($epreuveExamen->fichier) Storage::disk('public')->delete($epreuveExamen->fichier);
        if ($epreuveExamen->fichier_corrige) Storage::disk('public')->delete($epreuveExamen->fichier_corrige);

        $epreuveExamen->delete();

        $redirectTo = request()->input('redirect_to');
        if ($redirectTo && str_starts_with($redirectTo, url('/'))) {
            return redirect($redirectTo)->with('success', 'Épreuve supprimée.');
        }
        return redirect()->route('teacher.epreuves-examen.index')->with('success', 'Épreuve supprimée.');
    }
}