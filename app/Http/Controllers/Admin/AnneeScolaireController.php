<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeScolaire;
use App\Models\ClasseModel;
use App\Models\FraisScolarite;
use App\Models\ZoneTransport;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Affectation;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeScolaireController extends Controller
{
    public function index()
    {
        $annees = AnneeScolaire::orderByDesc('date_debut')->get();
        return view('admin.annees-scolaires.index', compact('annees'));
    }

    public function create() { return view('admin.annees-scolaires.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle'    => 'required|string|max:20|unique:annees_scolaires,libelle',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        AnneeScolaire::create($validated);

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année créée. Activez-la pour l\'initialiser.');
    }

    /**
     * ── LOGIQUE UNIQUE ──
     *
     * SI $anneeScolaire->initialisee == false :
     *   → Toute première activation. On copie les DONNÉES STRUCTURELLES
     *     (élèves, classes, matières, groupes, enseignants, affectations,
     *     niveaux, comptes users, transport, frais, config établissement)
     *     depuis la dernière année déjà initialisée.
     *   → Les DONNÉES OPÉRATIONNELLES (scolarité, bulletins, notes, paiements,
     *     candidatures, tableau d'honneur, cartes, conseils, relevés,
     *     absences, emploi du temps, PV) NE SONT JAMAIS COPIÉES — l'année
     *     démarre "vierge" sur ces aspects.
     *   → On marque initialisee = true, définitivement.
     *
     * SI $anneeScolaire->initialisee == true :
     *   → Retour en arrière ou en avant sur une année déjà vécue.
     *   → AUCUNE copie, AUCUNE suppression. On lit/modifie fidèlement
     *     ce qui existe déjà pour cette année précise.
     */
    public function activer(AnneeScolaire $anneeScolaire)
    {
        DB::transaction(function () use ($anneeScolaire) {

            $ancienneActive = AnneeScolaire::where('active', true)->where('id','!=',$anneeScolaire->id)->first();

            AnneeScolaire::where('active', true)->update(['active' => false]);
            $anneeScolaire->update(['active' => true]);

            if ($anneeScolaire->initialisee) {
                // ── Retour sur une année déjà vécue : lecture pure ──
                $this->resynchroniserClasses($anneeScolaire);
                return;
            }

            // ── Première activation : recherche de l'année source ──
            $anneeSource = AnneeScolaire::where('initialisee', true)
                ->where('id', '!=', $anneeScolaire->id)
                ->orderByDesc('date_debut')
                ->first();

            if ($anneeSource) {
                $this->marquerDiplomes($anneeSource, $anneeScolaire);
                $mapClasses = $this->copierStructure($anneeSource, $anneeScolaire);
                $this->glisserElevesActifs($anneeSource, $anneeScolaire, $mapClasses);
            }

            $anneeScolaire->update(['initialisee' => true]);
            $this->resynchroniserClasses($anneeScolaire);
        });

        $msg = $anneeScolaire->fresh()->initialisee
            ? "Année {$anneeScolaire->libelle} active."
            : "Année {$anneeScolaire->libelle} initialisée avec succès.";

        return back()->with('success', $msg);
    }

    /**
     * Marque diplômés les élèves qui étaient en classe TERMINALE l'année
     * source, et qui n'ont pas déjà de suite prévue. Ils ne sont PAS
     * supprimés — juste retirés du flux actif (statut = diplome).
     */
    protected function marquerDiplomes(AnneeScolaire $source, AnneeScolaire $cible): void
    {
        $elevesTerminaux = Eleve::whereHas('scolarites', function ($q) use ($source) {
                $q->where('annee_scolaire_id', $source->id)
                  ->whereHas('classe.niveau', fn($q2) => $q2->where('est_terminale', true));
            })
            ->where('statut', 'actif')
            ->get();

        foreach ($elevesTerminaux as $eleve) {
            $eleve->update(['statut' => 'diplome', 'classe_id' => null]);
        }
    }

    /**
     * Copie UNIQUEMENT les données structurelles :
     * Classes, Matières (classe_matiere + groupes), Niveaux (déjà globaux,
     * pas liés à l'année), Enseignants (déjà globaux), Affectations,
     * Zones de transport, Grilles de frais.
     * Config établissement est globale (une seule ligne) — jamais dupliquée.
     */
    protected function copierStructure(AnneeScolaire $source, AnneeScolaire $cible): array
    {
        $mapClasses = [];

        // ── Classes + matières (classe_matiere) ──
        $anciennesClasses = ClasseModel::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciennesClasses as $ancienneClasse) {
            $nouvelleClasse = ClasseModel::create([
                'nom'                     => $ancienneClasse->nom,
                'niveau_id'               => $ancienneClasse->niveau_id,
                'section_id'              => $ancienneClasse->section_id,
                'annee_scolaire_id'       => $cible->id,
                'capacite_max'            => $ancienneClasse->capacite_max,
                'professeur_principal_id' => null, // à réaffecter par l'admin
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

        // ── Affectations enseignants (classe → matière → enseignant) ──
        $anciennesAffectations = Affectation::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciennesAffectations as $aff) {
            if (!isset($mapClasses[$aff->classe_id])) continue;
            Affectation::firstOrCreate([
                'enseignant_id'     => $aff->enseignant_id,
                'matiere_id'        => $aff->matiere_id,
                'classe_id'         => $mapClasses[$aff->classe_id],
                'annee_scolaire_id' => $cible->id,
            ]);
        }

        // ── Grilles de frais ──
        $anciensFrais = FraisScolarite::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciensFrais as $f) {
            FraisScolarite::firstOrCreate(
                ['annee_scolaire_id' => $cible->id, 'section_id' => $f->section_id, 'niveau' => $f->niveau],
                ['frais_inscription' => $f->frais_inscription, 'tranche1' => $f->tranche1, 'tranche2' => $f->tranche2, 'tranche3' => $f->tranche3]
            );
        }

        // ── Zones de transport ──
        $anciennesZones = ZoneTransport::where('annee_scolaire_id', $source->id)->get();
        foreach ($anciennesZones as $z) {
            ZoneTransport::firstOrCreate(
                ['annee_scolaire_id' => $cible->id, 'nom' => $z->nom],
                ['quartiers' => $z->quartiers, 'montant' => $z->montant, 'actif' => $z->actif]
            );
        }

        // Niveaux, Enseignants, Comptes utilisateurs, Config établissement :
        // déjà GLOBAUX (non liés à annee_scolaire_id) — rien à copier, ils existent déjà.

        // ── Trimestres/séquences de la nouvelle année (structure vide, à saisir) ──
        for ($t = 1; $t <= 3; $t++) {
            $trimestre = \App\Models\Trimestre::firstOrCreate(
                ['annee_scolaire_id' => $cible->id, 'numero' => $t],
                ['nom' => "Trimestre {$t}"]
            );
            for ($s = 1; $s <= 2; $s++) {
                $numSeq = ($t - 1) * 2 + $s;
                \App\Models\Sequence::firstOrCreate(
                    ['trimestre_id' => $trimestre->id, 'numero' => $numSeq],
                    ['nom' => "Séquence {$numSeq}"]
                );
            }
        }

        return $mapClasses;
    }

    /**
     * Fait "glisser" chaque élève ACTIF (non diplômé) vers sa classe
     * équivalente de la nouvelle année, en créant une Scolarite PLACEHOLDER
     * (frais à 0, à compléter). C'est ce qui rend le glissement persistant :
     * l'élève reste visible même après plusieurs va-et-vient entre années.
     */
    protected function glisserElevesActifs(AnneeScolaire $source, AnneeScolaire $cible, array $mapClasses): void
    {
        if (empty($mapClasses)) return;

        $scolaritesSource = \App\Models\Scolarite::where('annee_scolaire_id', $source->id)
            ->whereHas('eleve', fn($q) => $q->where('statut', 'actif'))
            ->get(['eleve_id', 'classe_id']);

        foreach ($scolaritesSource as $sc) {
            if (!isset($mapClasses[$sc->classe_id])) continue;

            $existeDeja = \App\Models\Scolarite::where('eleve_id', $sc->eleve_id)
                ->where('annee_scolaire_id', $cible->id)->exists();
            if ($existeDeja) continue;

            \App\Models\Scolarite::create([
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
     * Seule source de vérité pour l'affichage : eleve.classe_id = classe
     * de la Scolarite enregistrée pour CETTE année précise. Fonctionne
     * identiquement pour un retour en arrière ou un retour en avant —
     * jamais de copie ici, juste un miroir fidèle de ce qui existe.
     */
    protected function resynchroniserClasses(AnneeScolaire $annee): void
    {
        DB::table('eleves')
            ->join('scolarites', function ($join) use ($annee) {
                $join->on('scolarites.eleve_id', '=', 'eleves.id')
                     ->where('scolarites.annee_scolaire_id', $annee->id);
            })
            ->where('eleves.statut', '!=', 'diplome')
            ->update(['eleves.classe_id' => DB::raw('scolarites.classe_id')]);

        DB::table('eleves')
            ->where('statut', '!=', 'diplome')
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
        if ($anneeScolaire->initialisee) {
            return back()->with('error', 'Impossible de supprimer une année déjà initialisée (contient des données réelles).');
        }
        $anneeScolaire->delete();
        return back()->with('success', 'Année supprimée.');
    }
}