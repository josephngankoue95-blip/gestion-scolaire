<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\Enseignant;
use App\Models\Absence;
use App\Models\Scolarite;
use App\Models\Sequence;
use App\Models\Trimestre;
use App\Models\AnneeScolaire;
use App\Services\MoyenneService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    public function index()
    {
        $user = Auth::user();
   
    if ($user->hasRole('admin'))               return $this->dashboardAdmin();
    if ($user->hasRole('proviseur'))           return redirect()->route('proviseur.dashboard');
    if ($user->hasRole('enseignant'))          return $this->dashboardEnseignant($user);
    if ($user->hasRole('surveillant_general')) return $this->dashboardSurveillant();
    if ($user->hasRole('secretaire_intendant'))return redirect()->route('secretaire.dashboard');
    if ($user->hasRole('bibliothecaire'))      return redirect()->route('bibliotheque.dashboard');
    if ($user->hasRole('parent'))              return redirect()->route('parent.dashboard');
    if ($user->hasRole('prefet_etudes'))       return redirect()->route('prefet.dashboard');
    if ($user->hasRole('eleve'))               return redirect()->route('eleve.dashboard');
       
    abort(403, "Aucun rôle valide n'est assigné à ce compte.");
    return view('dashboard.default');
    }

    protected function dashboardAdmin()
    {
        $annee = AnneeScolaire::getActive();

        // ── Stats globales ──
        $totalEleves      = Eleve::where('statut', 'actif')->count();
        $totalEnseignants = Enseignant::where('statut', 'actif')->count();
        $totalClasses     = ClasseModel::where('annee_scolaire_id', $annee?->id)->count();
        $absAujourdhui    = Absence::whereDate('date_absence', today())->count();
        $absNonJustif     = Absence::where('justifiee', false)->whereMonth('date_absence', now()->month)->count();

        // ── Scolarité ──
        $scolarites = Scolarite::where('annee_scolaire_id', $annee?->id)->get();
        $totalDu    = $scolarites->sum(fn($s) => $s->totalDu());
        $totalPaye  = $scolarites->sum(fn($s) => $s->totalPaye());
        $nbSoldes   = $scolarites->filter(fn($s) => $s->solde() <= 0)->count();
        $nbDettes   = $scolarites->filter(fn($s) => $s->solde() > 0)->count();
        $tauxRecouvrement = $totalDu > 0 ? round(($totalPaye / $totalDu) * 100, 1) : 0;

        // ── Effectifs par classe ──
        $effectifsClasses = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with(['section', 'niveau'])
            ->withCount(['eleves as nb_eleves'])
            ->orderBy('niveau_id')
            ->get();

        // ── Taux de réussite par séquence (dernière séquence disponible) ──
        $derniereSequence = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
            ->orderByDesc('numero')->first();

        $tauxReussiteParClasse = [];
        if ($derniereSequence) {
            foreach ($effectifsClasses as $classe) {
                $classe->load([
                    'matieres',
                    'section',
                    'niveau',
                    'eleves'
                ]); 
                $elevesClasse = $classe->eleves()->get();

                if ($elevesClasse->isEmpty()) continue;

                $nbReussis = 0;
                foreach ($elevesClasse as $eleve) {
                    $bulletin = $this->svc->construireBulletinSequence($eleve, $classe, $derniereSequence);
                    if (($bulletin['moyenne_generale'] ?? 0) >= 10) $nbReussis++;
                }

                $tauxReussiteParClasse[] = [
                    'classe'  => $classe->nom,
                    'section' => $classe->section->code,
                    'effectif'=> $elevesClasse->count(),
                    'reussis' => $nbReussis,
                    'taux'    => $elevesClasse->count() > 0
                        ? round(($nbReussis / $elevesClasse->count()) * 100, 1)
                        : 0,
                    'sequence'=> $derniereSequence->nom,
                ];
            }
        }

        // ── Absences par classe ce mois ──
        $absencesParClasse = DB::table('absences')
            ->join('eleves', 'absences.eleve_id', '=', 'eleves.id')
            ->join('scolarites', function($join) use ($annee) {
                $join->on('scolarites.eleve_id', '=', 'eleves.id')
                     ->where('scolarites.annee_scolaire_id', $annee?->id);
            })
            ->join('classes', 'scolarites.classe_id', '=', 'classes.id')
            ->whereMonth('absences.date_absence', now()->month)
            ->whereYear('absences.date_absence', now()->year)
            ->groupBy('classes.id', 'classes.nom')
            ->select('classes.nom as classe', DB::raw('COUNT(*) as total'),
                     DB::raw('SUM(CASE WHEN absences.justifiee = 0 THEN 1 ELSE 0 END) as non_justifiees'))
            ->get();

        // ── Top 5 élèves en retard de paiement ──
        $dettesEleves = Scolarite::where('annee_scolaire_id', $annee?->id)
            ->with('eleve','classe')
            ->get()
            ->filter(fn($s) => $s->solde() > 0)
            ->sortByDesc(fn($s) => $s->solde())
            ->take(5);

        return view('dashboard.admin', compact(
            'annee', 'totalEleves', 'totalEnseignants', 'totalClasses',
            'absAujourdhui', 'absNonJustif',
            'totalDu', 'totalPaye', 'nbSoldes', 'nbDettes', 'tauxRecouvrement',
            'effectifsClasses', 'tauxReussiteParClasse', 'absencesParClasse',
            'dettesEleves', 'derniereSequence'
        ));
    }

    protected function dashboardEnseignant($user)
    {
        $enseignant   = $user->enseignant;
        $annee        = AnneeScolaire::getActive();
        $affectations = $enseignant?->affectationsAnneeActive()->get() ?? collect();
        $classesCount = $affectations->pluck('classe_id')->unique()->count();
        $matieresCount= $affectations->pluck('matiere_id')->unique()->count();

        $tdActifs = \App\Models\TravailDirige::where('enseignant_id', $enseignant?->id)
            ->where('annee_scolaire_id', $annee?->id)
            ->where('publie', true)
            ->where('date_limite_acces', '>=', now())
            ->count();

        return view('dashboard.enseignant', compact('enseignant','affectations','classesCount','matieresCount','tdActifs'));
    }

    protected function dashboardSurveillant()
{
    $absAujourdhui = Absence::whereDate('date_absence', today())
        ->with('eleve','classe')
        ->get();

    $absNonJust = Absence::where('justifiee', false)->count();

    $absSemaine = Absence::whereBetween('date_absence', [
        now()->startOfWeek(),
        now()->endOfWeek()
    ])->count();


    return view('dashboard.surveillant', compact(
        'absAujourdhui',
        'absNonJust',
        'absSemaine'
    ));
}

    protected function dashboardSecretaire()
    {
        $annee = AnneeScolaire::getActive();
        $scolarites = Scolarite::where('annee_scolaire_id', $annee?->id)->get();
        $totalDu    = $scolarites->sum(fn($s) => $s->totalDu());
        $totalPaye  = $scolarites->sum(fn($s) => $s->totalPaye());
        $nbDettes   = $scolarites->filter(fn($s) => $s->solde() > 0)->count();

        $paiementsRecents = \App\Models\PaiementScolarite::with('scolarite.eleve')
            ->latest()->limit(5)->get();

        return view('dashboard.secretaire', compact('totalDu','totalPaye','nbDettes','paiementsRecents','annee'));
    }
}