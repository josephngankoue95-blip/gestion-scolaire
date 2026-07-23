<?php
namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\AnneeScolaire;
use App\Models\Trimestre;
use App\Models\Sequence;
use App\Models\ClasseModel;
use App\Services\MoyenneService;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    protected function mesEnfants()
    {
        return Eleve::where('parent_user_id', Auth::id())->get();
    }

    public function dashboard()
    {
        $enfants = $this->mesEnfants();
        $annee   = AnneeScolaire::getActive();

        $data = $enfants->map(function ($eleve) use ($annee) {
            $scolarite = $eleve->scolarites()
                ->where('annee_scolaire_id', $annee?->id)
                ->with('classe.section')
                ->first();
            return [
                'eleve'     => $eleve,
                'scolarite' => $scolarite,
            ];
        });

        return view('parent.dashboard', compact('data', 'annee'));
    }

    public function bulletins(Request $request)
    {
        $enfants  = $this->mesEnfants();
        $annee    = AnneeScolaire::getActive();
        $enfant   = $enfants->firstWhere('id', $request->eleve_id) ?? $enfants->first();

        abort_if(!$enfant, 403);

        $scolarite = $enfant->scolariteActive();
        $trimestres = Trimestre::where('annee_scolaire_id', $annee?->id)->orderBy('numero')->get();
        $sequences  = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))->with('trimestre')->orderBy('numero')->get();

        return view('parent.bulletins', compact('enfant', 'enfants', 'scolarite', 'trimestres', 'sequences'));
    }

    public function voirBulletin(Request $request, Eleve $eleve)
    {
        // Vérification que c'est bien son enfant
        abort_if($eleve->parent_user_id !== Auth::id(), 403);

        $inscription = $eleve->scolariteActive();
        abort_if(!$inscription, 404, 'Élève non inscrit pour cette année.');

        $classe = $inscription->classe->load('section','anneeScolaire','professeurPrincipal.user','matieres');
        $lang   = strtolower($classe->section->code) === 'ang' ? 'en' : 'fr';
        $lang = strtolower($classe->section->code) === 'ang' ? 'en' : 'fr';
        $etablissement = Etablissement::instance();

        $request->validate([
            'type'         => 'required|in:sequentiel,trimestriel,annuel',
            'periode_id'   => 'nullable|integer',
        ]);


        if ($request->type === 'sequentiel') {
            $sequence = Sequence::with('trimestre')->findOrFail($request->periode_id);
            $bulletin = $this->svc->construireBulletinSequence($eleve, $classe, $sequence);
            $pdf = Pdf::loadView('admin.bulletins.pdf-sequence', compact('eleve','classe','bulletin','etablissement') + ['periode' => $sequence, 'etablissement' => Etablissement::instance(), 'lang' => $lang]);
        } elseif ($request->type === 'trimestriel') {
            $trimestre = Trimestre::findOrFail($request->periode_id);
            $bulletin  = $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre);
            $pdf = Pdf::loadView('admin.bulletins.pdf-trimestre', ['eleve' => $eleve,'classe' => $classe,'bulletin' => $bulletin,'periode' => $trimestre, 'etablissement' => Etablissement::instance(), 'lang' => $lang]);
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)->orderBy('numero')->with('sequences')->get();
            $bulletin   = $this->svc->bulletinAnnuel($eleve, $classe, $trimestres->all());
            $pdf = Pdf::loadView('admin.bulletins.pdf-annuel', ['eleve' => $eleve,'classe' => $classe,'bulletin' => $bulletin,'etablissement' => Etablissement::instance(),'lang' => $lang]);
        }

        return $pdf->setPaper('a4','portrait')->stream("bulletin_{$eleve->matricule}.pdf");
    }

    public function emploiDuTemps(Request $request)
    {
        $enfants = $this->mesEnfants();
        $enfant  = $enfants->firstWhere('id', $request->eleve_id) ?? $enfants->first();

        abort_if(!$enfant, 403);

        $scolarite = $enfant->scolariteActive();
        $creneaux  = collect();
        $jours     = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];

        if ($scolarite) {
            $creneaux = \App\Models\EmploiTemps::where('classe_id', $scolarite->classe_id)
                ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
                ->with('matiere','enseignant.user')
                ->get()
                ->groupBy('jour');
        }

        return view('parent.emploi-du-temps', compact('enfant','enfants','scolarite','creneaux','jours'));
    }

    public function absences(Request $request)
    {
        $enfants = $this->mesEnfants();
        $enfant  = $enfants->firstWhere('id', $request->eleve_id) ?? $enfants->first();

        abort_if(!$enfant, 403);

        $absences = \App\Models\Absence::where('eleve_id', $enfant->id)
            ->orderByDesc('date_absence')
            ->paginate(20);

        return view('parent.absences', compact('enfant','enfants','absences'));
    }

    public function scolarite(Request $request)
{
    $enfants = $this->mesEnfants();
    $enfant  = $enfants->firstWhere('id', $request->eleve_id) ?? $enfants->first();

    abort_if(!$enfant, 403);

    $scolarite = $enfant->scolariteActive();

    return view('parent.scolarite', compact('enfant', 'enfants', 'scolarite'));
}
}