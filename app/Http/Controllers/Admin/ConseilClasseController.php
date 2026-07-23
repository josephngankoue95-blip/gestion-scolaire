<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConseilClasse;
use App\Models\DecisionConseil;
use App\Models\ClasseModel;
use App\Models\Trimestre;
use App\Models\AnneeScolaire;
use App\Models\Etablissement;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConseilClasseController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    public function index(Request $request)
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('nom')->get();

        $query = ConseilClasse::with('classe.section', 'trimestre', 'president')
            ->where('annee_scolaire_id', $annee?->id);

        if ($request->filled('classe_id')) $query->where('classe_id', $request->classe_id);

        $conseils = $query->latest('date_conseil')->paginate(15);

        return view('admin.conseils.index', compact('conseils', 'classes', 'annee'));
    }

    public function create()
    {
        $annee      = AnneeScolaire::getActive();
        $classes    = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('nom')->get();
        $trimestres = Trimestre::where('annee_scolaire_id', $annee?->id)->orderBy('numero')->get();

        return view('admin.conseils.create', compact('classes', 'trimestres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'trimestre_id'=> 'nullable|exists:trimestres,id',
            'date_conseil'=> 'required|date',
            'preside_par' => 'nullable|exists:users,id',
        ]);

        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;
        $validated['cree_par']          = Auth::id();

        $conseil = ConseilClasse::create($validated);

        return redirect()->route('admin.conseils.show', $conseil)
            ->with('success', 'Conseil de classe créé. Vous pouvez maintenant enregistrer les décisions.');
    }

    public function show(ConseilClasse $conseil)
    {
        $conseil->load('classe.section', 'trimestre', 'president', 'decisions.eleve');

        $eleves = $conseil->classe->eleves()->orderBy('nom')->get();

        // Moyennes calculées pour aider à la décision (si un trimestre est lié)
        $moyennes = [];
        if ($conseil->trimestre) {
            foreach ($eleves as $eleve) {
                $b = $this->svc->construireBulletinTrimestre($eleve, $conseil->classe, $conseil->trimestre);
                $moyennes[$eleve->id] = $b['moyenne_generale'];
            }
        }

        return view('admin.conseils.show', compact('conseil', 'eleves', 'moyennes'));
    }

    public function storeDecision(Request $request, ConseilClasse $conseil)
    {
        $validated = $request->validate([
            'eleve_id'         => 'required|exists:eleves,id',
            'type_decision'    => 'required|in:passage,redoublement,exclusion_temporaire,exclusion_definitive,avertissement,felicitations,encouragements,blame,tableau_honneur,autre',
            'motif'            => 'nullable|string|max:500',
            'observation'      => 'nullable|string|max:500',
            'date_application' => 'nullable|date',
        ]);

        $validated['conseil_classe_id'] = $conseil->id;
        $validated['decidee_par']       = Auth::id();

        DecisionConseil::updateOrCreate(
            ['conseil_classe_id' => $conseil->id, 'eleve_id' => $validated['eleve_id']],
            $validated
        );

        return back()->with('success', 'Décision enregistrée.');
    }

    public function cloturer(ConseilClasse $conseil)
    {
        $conseil->update(['statut' => 'cloture']);
        return back()->with('success', 'Conseil de classe clôturé.');
    }

    public function pvPdf(ConseilClasse $conseil)
    {
        $conseil->load('classe.section', 'trimestre', 'president', 'decisions.eleve');

        $pdf = Pdf::loadView('admin.conseils.pv-pdf', [
            'conseil'       => $conseil,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("pv_conseil_{$conseil->classe->nom}.pdf");
    }

    public function destroy(ConseilClasse $conseil)
    {
        $conseil->delete();
        return redirect()->route('admin.conseils.index')->with('success', 'Conseil supprimé.');
    }
}