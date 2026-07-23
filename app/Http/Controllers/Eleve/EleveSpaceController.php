<?php
namespace App\Http\Controllers\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\AnneeScolaire;
use App\Models\Trimestre;
use App\Models\Sequence;
use App\Models\EmploiTemps;
use App\Models\TravailDirige;
use App\Models\Requete;
use App\Models\Absence;
use App\Models\Etablissement;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EleveSpaceController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    protected function monProfil(): Eleve
    {
        $eleve = Eleve::where('eleve_user_id', Auth::id())->first();

        abort_if(!$eleve, 404, "Aucun dossier élève n'est associé à ce compte. Contactez l'administration pour lier votre compte.");

        return $eleve;
    }

    // ── DASHBOARD ──────────────────────────────────────────────
    public function dashboard()
    {
        $eleve     = $this->monProfil();
        $annee     = AnneeScolaire::getActive();
        $scolarite = $eleve->scolariteActive();

        $classe = $scolarite?->classe?->load('section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres');

        $derniereSeq = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
            ->orderByDesc('numero')->first();

        $bulletinSeq = null;
        if ($derniereSeq && $classe) {
            $bulletinSeq = $this->svc->construireBulletinSequence($eleve, $classe, $derniereSeq);
        }

        $absencesMois = Absence::where('eleve_id', $eleve->id)
            ->whereMonth('date_absence', now()->month)
            ->whereYear('date_absence', now()->year)
            ->count();

        $absNonJust = Absence::where('eleve_id', $eleve->id)
            ->where('justifiee', false)
            ->whereMonth('date_absence', now()->month)
            ->count();

        $tdActifs = $scolarite
            ? TravailDirige::where('classe_id', $scolarite->classe_id)
                ->where('publie', true)
                ->where('date_publication', '<=', now())
                ->where('date_limite_acces', '>=', now())
                ->count()
            : 0;

        $requetesEnAttente = $eleve->requetes()->where('statut', 'en_attente')->count();

        // Prochains cours du jour (emploi du temps)
        $joursSemaine = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];
        $jourAujourdhui = $joursSemaine[now()->dayOfWeekIso - 1] ?? null;

        $coursAujourdhui = ($scolarite && $jourAujourdhui)
            ? EmploiTemps::where('classe_id', $scolarite->classe_id)
                ->where('annee_scolaire_id', $annee?->id)
                ->where('jour', $jourAujourdhui)
                ->with('matiere', 'enseignant.user')
                ->orderBy('heure_debut')
                ->get()
            : collect();

        return view('eleve.dashboard', compact(
            'eleve', 'classe', 'scolarite', 'bulletinSeq', 'derniereSeq',
            'absencesMois', 'absNonJust', 'tdActifs', 'requetesEnAttente', 'coursAujourdhui', 'jourAujourdhui'
        ));
    }

    // ── NOTES & BULLETINS ──────────────────────────────────────
    public function bulletins()
    {
        $eleve     = $this->monProfil();
        $annee     = AnneeScolaire::getActive();
        $scolarite = $eleve->scolariteActive();

        abort_if(!$scolarite, 404, "Vous n'êtes pas inscrit pour l'année scolaire active.");

        $classe     = $scolarite->classe->load('matieres', 'section', 'anneeScolaire', 'professeurPrincipal.user');
        $trimestres = Trimestre::where('annee_scolaire_id', $annee?->id)->orderBy('numero')->with('sequences')->get();
        $sequences  = Sequence::whereHas('trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
            ->with('trimestre')->orderBy('numero')->get();

        $moyennesSeq = $sequences->map(function ($seq) use ($eleve, $classe) {
            $b = $this->svc->construireBulletinSequence($eleve, $classe, $seq);
            return ['sequence' => $seq, 'moyenne' => $b['moyenne_generale'], 'rang' => $b['rang'], 'details' => $b['details']];
        });

        $moyennesTrim = $trimestres->map(function ($trim) use ($eleve, $classe) {
            $b = $this->svc->construireBulletinTrimestre($eleve, $classe, $trim);
            return ['trimestre' => $trim, 'moyenne' => $b['moyenne_generale'], 'rang' => $b['rang']];
        });

        // Notes détaillées de la dernière séquence, pour affichage direct (onglet "Notes")
        $derniereSeq = $sequences->last();
        $notesDetail = $derniereSeq
            ? $this->svc->construireBulletinSequence($eleve, $classe, $derniereSeq)['details']
            : [];

        return view('eleve.bulletins', compact(
            'eleve', 'scolarite', 'classe', 'trimestres', 'sequences',
            'moyennesSeq', 'moyennesTrim', 'derniereSeq', 'notesDetail'
        ));
    }

    public function voirBulletin(Request $request)
    {
        $eleve     = $this->monProfil();
        $scolarite = $eleve->scolariteActive();
        abort_if(!$scolarite, 404);

        $classe = $scolarite->classe->load('matieres', 'section', 'anneeScolaire', 'professeurPrincipal.user');
        $lang   = strtolower($classe->section->code) === 'ang' ? 'en' : 'fr';

        $request->validate([
            'type'       => 'required|in:sequentiel,trimestriel,annuel',
            'periode_id' => 'nullable|integer',
        ]);

        if ($request->type === 'sequentiel') {
            $periode  = Sequence::with('trimestre')->findOrFail($request->periode_id);
            $bulletin = $this->svc->construireBulletinSequence($eleve, $classe, $periode);
            $vue      = 'admin.bulletins.pdf-sequence';
        } elseif ($request->type === 'trimestriel') {
            $periode  = Trimestre::findOrFail($request->periode_id);
            $bulletin = $this->svc->construireBulletinTrimestre($eleve, $classe, $periode);
            $vue      = 'admin.bulletins.pdf-trimestre';
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)->orderBy('numero')->with('sequences')->get();
            $bulletin   = $this->svc->bulletinAnnuel($eleve, $classe, $trimestres->all());
            $vue        = 'admin.bulletins.pdf-annuel';
            $periode    = null;
        }

        $pdf = Pdf::loadView($vue, [
            'eleve' => $eleve, 'classe' => $classe, 'bulletin' => $bulletin,
            'periode' => $periode ?? null, 'etablissement' => Etablissement::instance(), 'lang' => $lang,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("bulletin_{$eleve->matricule}.pdf");
    }

    // ── EMPLOI DU TEMPS ─────────────────────────────────────────
    public function emploiDuTemps()
    {
        $eleve     = $this->monProfil();
        $annee     = AnneeScolaire::getActive();
        $scolarite = $eleve->scolariteActive();

        $jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];

        $creneaux = $scolarite
            ? EmploiTemps::where('classe_id', $scolarite->classe_id)
                ->where('annee_scolaire_id', $annee?->id)
                ->with('matiere', 'enseignant.user')
                ->get()
                ->groupBy('jour')
            : collect();

        return view('eleve.emploi-du-temps', compact('eleve', 'scolarite', 'jours', 'creneaux'));
    }

    // ── TRAVAUX DIRIGÉS ─────────────────────────────────────────
    public function travaux()
    {
        $eleve     = $this->monProfil();
        $scolarite = $eleve->scolariteActive();

        $tds = $scolarite
            ? TravailDirige::where('classe_id', $scolarite->classe_id)
                ->where('publie', true)->where('date_publication', '<=', now())
                ->with('matiere', 'enseignant.user')->orderByDesc('date_publication')->get()
            : collect();

        return view('eleve.travaux', compact('eleve', 'tds'));
    }

    public function voirTravail(TravailDirige $travailDirige)
    {
        $eleve     = $this->monProfil();
        $scolarite = $eleve->scolariteActive();

        abort_if($travailDirige->classe_id !== $scolarite?->classe_id, 403);
        abort_if(!$travailDirige->estAccessible(), 403, 'Ce TD n\'est plus accessible.');

        return view('eleve.travail-show', compact('travailDirige'));
    }

    // ── REQUÊTES ────────────────────────────────────────────────
    public function requetes()
    {
        $eleve    = $this->monProfil();
        $requetes = $eleve->requetes()->with('traitePar')->latest()->paginate(10);
        return view('eleve.requetes', compact('eleve', 'requetes'));
    }

    public function storeRequete(Request $request)
    {
        $eleve = $this->monProfil();

        $validated = $request->validate([
            'type'    => 'required|in:attestation,certificat_scolarite,bulletin,transfert,autre',
            'objet'   => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        $validated['eleve_id']          = $eleve->id;
        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;

        Requete::create($validated);

        return back()->with('success', 'Votre requête a été soumise avec succès.');
    }

    // ── PROFIL ──────────────────────────────────────────────────
    public function profil()
    {
        $eleve = $this->monProfil();
        $eleve->load('classe.section');
        $scolarite = $eleve->scolariteActive();
        return view('eleve.profil', compact('eleve', 'scolarite'));
    }

    public function updateProfil(Request $request)
    {
        $validated = $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            Auth::user()->update(['password' => Hash::make($validated['password'])]);
        }

        return back()->with('success', 'Mot de passe mis à jour.');
    }
}