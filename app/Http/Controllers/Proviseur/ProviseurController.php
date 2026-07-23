<?php
namespace App\Http\Controllers\Proviseur;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\Enseignant;
use App\Models\Absence;
use App\Models\Scolarite;
use App\Models\Sequence;
use App\Models\Trimestre;
use App\Models\AnneeScolaire;
use App\Models\Candidature;
use App\Models\Requete;
use App\Models\Transfert;
use App\Models\TravailDirige;
use App\Models\Etablissement;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviseurController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    // ── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        $annee = AnneeScolaire::getActive();

        $stats = [
            'eleves'      => Eleve::where('statut','actif')->count(),
            'enseignants' => Enseignant::where('statut','actif')->count(),
            'classes'     => ClasseModel::where('annee_scolaire_id', $annee?->id)->count(),
            'abs_today'   => Absence::whereDate('date_absence', today())->count(),
            'abs_mois_nj' => Absence::where('justifiee',false)->whereMonth('date_absence',now()->month)->count(),
            'candidatures_attente' => Candidature::where('statut','en_attente')->count(),
            'requetes_attente'     => Requete::where('statut','en_attente')->count(),
            'transferts'           => Transfert::where('annee_scolaire_id',$annee?->id)->count(),
        ];

        $scolarites     = Scolarite::where('annee_scolaire_id',$annee?->id)->get();
        $finance        = [
            'total_du'   => $scolarites->sum(fn($s) => $s->totalDu()),
            'total_paye' => $scolarites->sum(fn($s) => $s->totalPaye()),
            'nb_soldes'  => $scolarites->filter(fn($s) => $s->solde() <= 0)->count(),
            'nb_dettes'  => $scolarites->filter(fn($s) => $s->solde() > 0)->count(),
        ];
        $finance['taux_recouv'] = $finance['total_du'] > 0
            ? round(($finance['total_paye']/$finance['total_du'])*100, 1)
            : 0;

        $effectifsClasses = ClasseModel::where('annee_scolaire_id',$annee?->id)
            ->with('section')
           ->withCount('eleves')
            ->orderBy('niveau_id')->get();

        return view('proviseur.dashboard', compact('stats','finance','effectifsClasses','annee'));
    }

    // ── Scolarité (lecture seule) ──────────────────────────────
    public function scolarite(Request $request)
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id',$annee?->id)->with('section')->get();

        $query = Scolarite::with('eleve','classe.section')
            ->where('annee_scolaire_id',$annee?->id);

        if ($request->filled('classe_id')) $query->where('classe_id',$request->classe_id);

        $scolarites = $query->paginate(25);

        // Stats par classe
        $statsClasses = DB::table('scolarites')
            ->join('classes','scolarites.classe_id','=','classes.id')
            ->join('sections','classes.section_id','=','sections.id')
            ->where('scolarites.annee_scolaire_id',$annee?->id)
            ->groupBy('classes.id','classes.nom','sections.code')
            ->select(
                'classes.nom as classe',
                'sections.code as section',
                DB::raw('COUNT(*) as nb_inscrits'),
                DB::raw('SUM(frais_inscription+montant_tranche1+montant_tranche2+montant_tranche3+montant_transport) as total_du'),
                DB::raw('SUM(paye_inscription+paye_tranche1+paye_tranche2+paye_tranche3+paye_transport) as total_paye')
            )->get()
            ->map(fn($c) => [...(array)$c,
                'solde' => $c->total_du - $c->total_paye,
                'taux'  => $c->total_du > 0 ? round(($c->total_paye/$c->total_du)*100,1) : 0
            ]);

        return view('proviseur.scolarite', compact('scolarites','classes','statsClasses','annee'));
    }

    // ── Performances ───────────────────────────────────────────
    public function performances(Request $request)
    {
        $annee     = AnneeScolaire::getActive();
        $classes   = ClasseModel::where('annee_scolaire_id',$annee?->id)->with('section','matieres')->orderBy('niveau_id')->get();
        $sequences = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id',$annee?->id))
            ->with('trimestre')->orderBy('numero')->get();

        $selectedSeqId = $request->filled('sequence_id') ? $request->sequence_id : $sequences->last()?->id;
        $selectedSeq   = $sequences->firstWhere('id', $selectedSeqId);

        $resultats = [];
        if ($selectedSeq) {
            foreach ($classes as $classe) {
                $eleves = $classe->eleves()->get();
                if ($eleves->isEmpty()) continue;

                $moyennes  = [];
                $reussis   = 0;
                foreach ($eleves as $eleve) {
                    $b = $this->svc->construireBulletinSequence($eleve, $classe, $selectedSeq);
                    $moy = $b['moyenne_generale'];
                    if ($moy !== null) {
                        $moyennes[] = $moy;
                        if ($moy >= 10) $reussis++;
                    }
                }

                $resultats[] = [
                    'classe'   => $classe->nom,
                    'section'  => $classe->section->code,
                    'effectif' => $eleves->count(),
                    'reussis'  => $reussis,
                    'taux'     => $eleves->count() > 0 ? round(($reussis/$eleves->count())*100,1) : 0,
                    'moy_classe' => count($moyennes) > 0 ? round(array_sum($moyennes)/count($moyennes),2) : null,
                    'moy_max'  => count($moyennes) > 0 ? max($moyennes) : null,
                    'moy_min'  => count($moyennes) > 0 ? min($moyennes) : null,
                ];
            }
        }

        return view('proviseur.performances', compact('resultats','sequences','selectedSeq','annee'));
    }

    // ── Absences ───────────────────────────────────────────────
    public function absences(Request $request)
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id',$annee?->id)->with('section')->get();

        $query = Absence::with('eleve','classe','signalePar');
        if ($request->filled('classe_id')) $query->where('classe_id',$request->classe_id);
        if ($request->filled('mois'))      $query->whereMonth('date_absence',$request->mois);

        $absences = $query->latest('date_absence')->paginate(25);

        $statsParClasse = DB::table('absences')
            ->join('eleves','absences.eleve_id','=','eleves.id')
            ->join('scolarites',fn($j) => $j->on('scolarites.eleve_id','=','eleves.id')->where('scolarites.annee_scolaire_id',$annee?->id))
            ->join('classes','scolarites.classe_id','=','classes.id')
            ->groupBy('classes.id','classes.nom')
            ->select('classes.nom as classe', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN absences.justifiee=0 THEN 1 ELSE 0 END) as nj'))
            ->get();

        $elevesAbsents = DB::table('absences')
            ->join('eleves','absences.eleve_id','=','eleves.id')
            ->groupBy('eleves.id','eleves.nom','eleves.prenom','eleves.matricule')
            ->select('eleves.nom','eleves.prenom','eleves.matricule',DB::raw('COUNT(*) as total'),DB::raw('SUM(CASE WHEN absences.justifiee=0 THEN 1 ELSE 0 END) as nj'))
            ->orderByDesc('total')->limit(10)->get();

        return view('proviseur.absences', compact('absences','statsParClasse','elevesAbsents','classes','annee'));
    }

    // ── Enseignants ─────────────────────────────────────────────
    public function enseignants()
    {
        $annee       = AnneeScolaire::getActive();
        $enseignants = Enseignant::with('user','affectations.matiere','affectations.classe.section')
            ->where('statut','actif')->get()
            ->map(function ($ens) use ($annee) {
                $ens->nb_affectations = $ens->affectations->where('annee_scolaire_id',$annee?->id)->count();
                $ens->nb_td = TravailDirige::where('enseignant_id',$ens->id)->where('annee_scolaire_id',$annee?->id)->count();
                return $ens;
            });

        return view('proviseur.enseignants', compact('enseignants','annee'));
    }

    // ── Rapport PDF global ──────────────────────────────────────
    public function rapportPdf()
    {
        $annee      = AnneeScolaire::getActive();
        $scolarites = Scolarite::where('annee_scolaire_id',$annee?->id)->get();
        $totalDu    = $scolarites->sum(fn($s) => $s->totalDu());
        $totalPaye  = $scolarites->sum(fn($s) => $s->totalPaye());

        $effectifs  = ClasseModel::where('annee_scolaire_id',$annee?->id)
            ->with('section')
            ->withCount('eleves as nb_eleves')
            ->orderBy('niveau_id')->get();

        $pdf = Pdf::loadView('proviseur.rapport-pdf', [
            'annee'         => $annee,
            'totalDu'       => $totalDu,
            'totalPaye'     => $totalPaye,
            'effectifs'     => $effectifs,
            'totalEleves'   => Eleve::where('statut','actif')->count(),
            'totalEns'      => Enseignant::where('statut','actif')->count(),
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4','portrait');

        return $pdf->stream("rapport_proviseur_{$annee?->libelle}.pdf");
    }
}