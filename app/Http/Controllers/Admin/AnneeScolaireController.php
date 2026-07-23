<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeScolaire;
use App\Models\ClasseModel;
use App\Models\FraisScolarite;
use App\Models\ZoneTransport;
use App\Models\Trimestre;
use App\Models\Sequence;
use App\Models\Eleve;
use App\Models\Scolarite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeScolaireController extends Controller
{
    public function index()
    {
        $annees = AnneeScolaire::orderByDesc('date_debut')->get();
        return view('admin.annees-scolaires.index', compact('annees'));
    }

    public function create()
    {
        return view('admin.annees-scolaires.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle'    => 'required|string|max:20|unique:annees_scolaires,libelle',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        AnneeScolaire::create($validated); // initialisee = false par défaut

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année créée. Activez-la pour initialiser ses données.');
    }

    /**
     * Active une année scolaire.
     *
     * RÈGLE UNIQUE ET SIMPLE :
     * - Si $anneeScolaire->initialisee == false → c'est la toute première fois qu'on
     *   l'active → on copie les classes/matières/frais/zones/trimestres depuis
     *   L'ANNÉE LA PLUS RÉCENTE QUI EST DÉJÀ INITIALISÉE (peu importe si elle est
     *   "active" au moment du clic — on ne dépend plus du flag active, qui est
     *   trop fragile). Puis on marque initialisee = true, définitivement.
     *
     * - Si $anneeScolaire->initialisee == true → on ne copie RIEN, on ne touche
     *   à AUCUNE donnée. On se contente de resynchroniser l'affichage
     *   (eleve.classe_id) à partir des Scolarite déjà enregistrées pour cette
     *   année précise. C'est un simple "retour en arrière" en lecture fidèle.
     */
    public function activer(AnneeScolaire $anneeScolaire)
    {
        DB::transaction(function () use ($anneeScolaire) {

            // 1. Basculer le flag "active" (affichage global uniquement)
            AnneeScolaire::where('active', true)->update(['active' => false]);
            $anneeScolaire->update(['active' => true]);

            // 2. Si déjà initialisée → RIEN À COPIER, on sort après la resync finale
            if ($anneeScolaire->initialisee) {
                $this->resynchroniserClasses($anneeScolaire);
                return;
            }

            // 3. Première activation : chercher la meilleure année source à copier
            //    → la plus récente déjà initialisée (indépendant du flag "active")
            $anneeSource = AnneeScolaire::where('initialisee', true)
                ->where('id', '!=', $anneeScolaire->id)
                ->orderByDesc('date_debut')
                ->first();

            if ($anneeSource) {
                $mapClasses = $this->copierDonneesAnnee($anneeSource, $anneeScolaire);
                $this->glisserElevesSansScolarite($anneeSource, $anneeScolaire, $mapClasses);
            } else {
                // Aucune année source : on crée quand même les trimestres/séquences
                // pour que l'année soit utilisable, même vide (tout premier lancement du système)
                $this->creerTrimestresEtSequences($anneeScolaire);
            }

            // 4. Marquer cette année comme définitivement initialisée
            $anneeScolaire->update(['initialisee' => true]);

            // 5. Synchroniser l'affichage
            $this->resynchroniserClasses($anneeScolaire);
        });

        $msg = $anneeScolaire->fresh()->initialisee
            ? "Année {$anneeScolaire->libelle} active. Ses données sont fidèlement restaurées."
            : "Année {$anneeScolaire->libelle} activée pour la première fois.";

        return back()->with('success', $msg);
    }

    /** Copie classes + matières + frais + zones + trimestres depuis $source vers $cible */
    protected function copierDonneesAnnee(AnneeScolaire $source, AnneeScolaire $cible): array
    {
        $mapClasses = [];

        // Classes + matières
        $anciennesClasses = ClasseModel::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciennesClasses as $ancienneClasse) {
            $nouvelleClasse = ClasseModel::create([
                'nom'                     => $ancienneClasse->nom,
                'niveau_id'               => $ancienneClasse->niveau_id,
                'section_id'              => $ancienneClasse->section_id,
                'annee_scolaire_id'       => $cible->id,
                'capacite_max'            => $ancienneClasse->capacite_max,
                'professeur_principal_id' => null,
            ]);

            $matieres = DB::table('classe_matiere')->where('classe_id', $ancienneClasse->id)->get();
            foreach ($matieres as $cm) {
                DB::table('classe_matiere')->updateOrInsert(
                    ['classe_id' => $nouvelleClasse->id, 'matiere_id' => $cm->matiere_id],
                    ['coefficient' => $cm->coefficient, 'ordre' => $cm->ordre, 'groupe' => $cm->groupe]
                );
            }

            $mapClasses[$ancienneClasse->id] = $nouvelleClasse->id;
        }

        // Frais de scolarité
        $anciensFrais = FraisScolarite::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciensFrais as $f) {
            FraisScolarite::firstOrCreate(
                ['annee_scolaire_id' => $cible->id, 'section_id' => $f->section_id, 'niveau' => $f->niveau],
                ['frais_inscription' => $f->frais_inscription, 'tranche1' => $f->tranche1, 'tranche2' => $f->tranche2, 'tranche3' => $f->tranche3]
            );
        }

        // Zones de transport
        $anciennesZones = ZoneTransport::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciennesZones as $z) {
            ZoneTransport::firstOrCreate(
                ['annee_scolaire_id' => $cible->id, 'nom' => $z->nom],
                ['quartiers' => $z->quartiers, 'montant' => $z->montant, 'actif' => $z->actif]
            );
        }

        $this->creerTrimestresEtSequences($cible);

        return $mapClasses;
    }

    protected function creerTrimestresEtSequences(AnneeScolaire $annee): void
    {
        for ($t = 1; $t <= 3; $t++) {
            $trimestre = Trimestre::firstOrCreate(
                ['annee_scolaire_id' => $annee->id, 'numero' => $t],
                ['nom' => "Trimestre {$t}"]
            );
            for ($s = 1; $s <= 2; $s++) {
                $numSeq = ($t - 1) * 2 + $s;
                Sequence::firstOrCreate(
                    ['trimestre_id' => $trimestre->id, 'numero' => $numSeq],
                    ['nom' => "Séquence {$numSeq}"]
                );
            }
        }
    }

    /**
     * Crée une Scolarite (placeholder, frais à 0) pour chaque élève de $source
     * qui n'a pas encore de Scolarite pour $cible, en le plaçant dans sa classe
     * équivalente. Cela rend le glissement PERSISTANT (contrairement à un simple
     * cache) : l'élève reste retrouvable même après plusieurs va-et-vient.
     */
    protected function glisserElevesSansScolarite(AnneeScolaire $source, AnneeScolaire $cible, array $mapClasses): void
    {
        if (empty($mapClasses)) return;

        $elevesSource = Scolarite::where('annee_scolaire_id', $source->id)->get(['eleve_id', 'classe_id']);

        foreach ($elevesSource as $sc) {
            if (!isset($mapClasses[$sc->classe_id])) continue;

            $existeDeja = Scolarite::where('eleve_id', $sc->eleve_id)
                ->where('annee_scolaire_id', $cible->id)
                ->exists();

            if ($existeDeja) continue;

            Scolarite::create([
                'eleve_id'          => $sc->eleve_id,
                'classe_id'         => $mapClasses[$sc->classe_id],
                'annee_scolaire_id' => $cible->id,
                'date_inscription'  => $cible->date_debut,
                'type_inscription'  => 'nouvelle',
                'frais_inscription' => 0,
                'montant_tranche1'  => 0,
                'montant_tranche2'  => 0,
                'montant_tranche3'  => 0,
                'montant_transport' => 0,
            ]);
        }
    }

    /**
     * Seule et unique source de vérité pour l'affichage :
     * eleve.classe_id = classe de la Scolarite enregistrée pour CETTE année.
     * Aucune donnée n'est créée ni supprimée ici — juste un miroir fidèle.
     */
    protected function resynchroniserClasses(AnneeScolaire $annee): void
    {
        DB::table('eleves')
            ->join('scolarites', function ($join) use ($annee) {
                $join->on('scolarites.eleve_id', '=', 'eleves.id')
                     ->where('scolarites.annee_scolaire_id', $annee->id);
            })
            ->update(['eleves.classe_id' => DB::raw('scolarites.classe_id')]);

        DB::table('eleves')
            ->whereNotIn('id', function ($q) use ($annee) {
                $q->select('eleve_id')->from('scolarites')->where('annee_scolaire_id', $annee->id);
            })
            ->update(['classe_id' => null]);
    }

    public function destroy(AnneeScolaire $anneeScolaire)
    {
        if ($anneeScolaire->active) {
            return back()->with('error', 'Impossible de supprimer l\'année active.');
        }
        $anneeScolaire->delete();
        return back()->with('success', 'Année supprimée.');
    }
}