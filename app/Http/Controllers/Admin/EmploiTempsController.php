<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploiTemps;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class EmploiTempsController extends Controller
{
    protected array $jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];

    public function index(Request $request)
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with('section')
            ->orderBy('niveau_id')
            ->get();

        $classe   = null;
        $creneaux = collect();
        $matieres = collect();
        $enseignants = collect();

        // Si une classe est sélectionnée
        if ($request->filled('classe_id')) {
            $classe = ClasseModel::with('section', 'matieres')->findOrFail($request->classe_id);

            $creneaux = EmploiTemps::where('classe_id', $classe->id)
                ->where('annee_scolaire_id', $annee?->id)
                ->with('matiere', 'enseignant.user')
                ->get()
                ->groupBy('jour');

            $matieres = $classe->matieres()->orderBy('nom')->get();

            $enseignants = \App\Models\Enseignant::with('user')
                ->where('statut', 'actif')
                ->whereHas('affectations', function ($q) use ($classe, $annee) {
                    $q->where('classe_id', $classe->id)
                      ->where('annee_scolaire_id', $annee?->id);
                })
                ->get();
        }

        return view('admin.emplois-temps.index', [
            'classes'     => $classes,
            'classe'      => $classe,
            'creneaux'    => $creneaux,
            'matieres'    => $matieres,
            'enseignants' => $enseignants,
            'jours'       => $this->jours,
            'annee'       => $annee,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classe_id'    => 'required|exists:classes,id',
            'matiere_id'   => 'required|exists:matieres,id',
            'enseignant_id'=> 'required|exists:enseignants,id',
            'jour'         => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required|after:heure_debut',
            'salle'        => 'nullable|string|max:50',
        ]);

        $annee = AnneeScolaire::getActive();

        // Vérifier conflit d'horaire pour la classe
        $conflit = EmploiTemps::where('classe_id', $validated['classe_id'])
            ->where('annee_scolaire_id', $annee?->id)
            ->where('jour', $validated['jour'])
            ->where(function ($q) use ($validated) {
                $q->where(function ($q2) use ($validated) {
                    $q2->where('heure_debut', '<', $validated['heure_fin'])
                       ->where('heure_fin', '>', $validated['heure_debut']);
                });
            })->exists();

        if ($conflit) {
            return back()
                ->withInput()
                ->with('error', 'Conflit d\'horaire détecté pour cette classe sur ce créneau.');
        }

        // Vérifier conflit pour l'enseignant
        $conflitEns = EmploiTemps::where('enseignant_id', $validated['enseignant_id'])
            ->where('annee_scolaire_id', $annee?->id)
            ->where('jour', $validated['jour'])
            ->where(function ($q) use ($validated) {
                $q->where('heure_debut', '<', $validated['heure_fin'])
                  ->where('heure_fin', '>', $validated['heure_debut']);
            })->exists();

        if ($conflitEns) {
            return back()
                ->withInput()
                ->with('error', 'Cet enseignant a déjà un cours sur ce créneau horaire.');
        }

        $validated['annee_scolaire_id'] = $annee?->id;
        EmploiTemps::create($validated);

        return redirect()
            ->route('admin.emplois-temps.index', ['classe_id' => $validated['classe_id']])
            ->with('success', 'Créneau ajouté avec succès.');
    }

    public function destroy(EmploiTemps $emploiTemp)
    {
        $classeId = $emploiTemp->classe_id;
        $emploiTemp->delete();

        return redirect()
            ->route('admin.emplois-temps.index', ['classe_id' => $classeId])
            ->with('success', 'Créneau supprimé.');
    }
}