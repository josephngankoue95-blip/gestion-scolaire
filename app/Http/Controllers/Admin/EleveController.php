<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\User;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EleveController extends Controller
{
    public function index(Request $request)
{
    // Année scolaire active
    $annee = AnneeScolaire::getActive();

    // Liste des classes de l'année active
    $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
        ->orderBy('nom')
        ->get();

    // Construction de la requête
$query = Eleve::whereHas('scolarites', function ($q) use ($annee, $request) {

    $q->where('annee_scolaire_id', $annee->id);

    if ($request->filled('classe_id')) {
        $q->where('classe_id', $request->classe_id);
    }

});

    // Filtre par classe
    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    } else {
        // Au premier chargement, ne rien afficher
        $query->whereRaw('1 = 0');
    }

    // Recherche
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('matricule', 'like', "%{$search}%");
        });
    }

    // Récupération des élèves
    $eleves = $query
        ->orderBy('nom')
        ->orderBy('prenom')
        ->paginate(15)
        ->withQueryString();

    return view('admin.eleves.index', compact('eleves', 'classes'));
}

    public function export(Request $request)
    {
        return Excel::download(
            new ElevesExport($request->only(['search', 'classe_id'])),
            'eleves.xlsx'
        );
    }

public function create()
{
    $classes = \App\Models\ClasseModel::where('annee_scolaire_id', \App\Models\AnneeScolaire::getActive()?->id)
        ->with('section')->orderBy('nom')->get();
    return view('admin.eleves.create', compact('classes'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        // Élève
        'classe_id'        => 'required|exists:classes,id', // Adapter le nom de la table si nécessaire
        'nom'              => 'required|string|max:100',
        'prenom'           => 'required|string|max:100',
        'date_naissance'   => 'required|date|before:today',
        'lieu_naissance'   => 'nullable|string|max:100',
        'sexe'             => 'required|in:M,F',
        'telephone_parent' => 'nullable|string|max:20',
        'adresse'          => 'nullable|string|max:255',
        'photo'            => 'nullable|image|max:2048',

        // Compte parent
        'creer_compte_parent' => 'nullable|boolean',
        'parent_nom'          => 'nullable|string|max:150',
        'parent_email'        => 'nullable|email|max:150|unique:users,email',

        // Compte élève
        'creer_compte_eleve' => 'nullable|boolean',
        'eleve_email'        => 'nullable|email|max:150|unique:users,email',
    ]);

    DB::beginTransaction();

    try {

        // Matricule et statut
        $validated['matricule'] = Eleve::genererMatricule();
        $validated['statut'] = 'actif';

        // Photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('eleves', 'public');
        }

        $messages = [];

        /*
        |--------------------------------------------------------------------------
        | Création du compte Parent
        |--------------------------------------------------------------------------
        */
        if ($request->boolean('creer_compte_parent') && $request->filled('parent_email')) {

            $passwordParent = Str::random(10);

            $parent = User::create([
                'name'      => $request->parent_nom,
                'email'     => $request->parent_email,
                'telephone' => $request->telephone_parent,
                'password'  => Hash::make($passwordParent),
                'actif'     => true,
            ]);

            $parent->assignRole('parent');

            $validated['parent_user_id'] = $parent->id;

            $messages[] = "Parent : {$request->parent_email} / {$passwordParent}";
        }

        /*
        |--------------------------------------------------------------------------
        | Création du compte Élève
        |--------------------------------------------------------------------------
        */
        if ($request->boolean('creer_compte_eleve') && $request->filled('eleve_email')) {

            $passwordEleve = Str::random(10);

            $eleveUser = User::create([
                'name'     => $validated['prenom'].' '.$validated['nom'],
                'email'    => $request->eleve_email,
                'password' => Hash::make($passwordEleve),
                'actif'    => true,
            ]);

            $eleveUser->assignRole('eleve');

            $validated['eleve_user_id'] = $eleveUser->id;

            $messages[] = "Élève : {$request->eleve_email} / {$passwordEleve}";
        }

        /*
        |--------------------------------------------------------------------------
        | Création de l'élève
        |--------------------------------------------------------------------------
        */

        $eleve = Eleve::create($validated);

        DB::commit();

        $message = "L'élève {$eleve->nom} {$eleve->prenom} a été créé avec succès.";

        if (!empty($messages)) {
            $message .= " Comptes créés : ".implode(' | ', $messages);
        }

        $message .= " Rendez-vous dans le module Scolarité pour procéder à son inscription.";

        return redirect()
            ->route('admin.eleves.index')
            ->with('success', $message);

    } catch (\Exception $e) {

        DB::rollBack();

        if (!empty($validated['photo'])) {
            Storage::disk('public')->delete($validated['photo']);
        }

        return back()
            ->withInput()
            ->with('error', 'Erreur lors de la création de l\'élève : '.$e->getMessage());
    }
}

public function show(Eleve $eleve, Request $request)
{
    $eleve->load('classe.section');

    $toutesAnnees = \App\Models\AnneeScolaire::orderByDesc('date_debut')->get();

    $anneeConsultee = $request->filled('annee_id')
        ? \App\Models\AnneeScolaire::find($request->annee_id)
        : \App\Models\AnneeScolaire::getActive();

    $scolariteAnnee = $eleve->scolarites()
        ->where('annee_scolaire_id', $anneeConsultee?->id)
        ->with('classe.section', 'zoneTransport')
        ->first();

    return view('admin.eleves.show', compact('eleve', 'toutesAnnees', 'anneeConsultee', 'scolariteAnnee'));
}

public function edit(Eleve $eleve)
{
    $eleve->load('parentUser', 'eleveUser');
    return view('admin.eleves.edit', compact('eleve'));
}

public function update(Request $request, Eleve $eleve)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'date_naissance' => 'required|date|before:today',
        'lieu_naissance' => 'nullable|string|max:100',
        'sexe' => 'required|in:M,F',
        // 'nom_pere' => 'nullable|string|max:100',
        // 'nom_mere' => 'nullable|string|max:100',
        'telephone_parent' => 'nullable|string|max:20',
        // 'email_parent' => 'nullable|email|max:150',
        'adresse' => 'nullable|string|max:255',
        'statut' => 'required|in:actif,inactif,transfere,diplome',
        'photo' => 'nullable|image|max:2048',
        // Parent
        'creer_compte_parent' => 'nullable|boolean',
        'parent_email' => 'nullable|email|max:150',
        'parent_nom' => 'nullable|string|max:150',
        'parent_password' => 'nullable|string|min:6',
        // Élève
        'creer_compte_eleve' => 'nullable|boolean',
        'eleve_email' => 'nullable|email|max:150',
        'eleve_password' => 'nullable|string|min:6',
    ]);

    if ($request->hasFile('photo')) {
        if ($eleve->photo) Storage::disk('public')->delete($eleve->photo);
        $validated['photo'] = $request->file('photo')->store('eleves', 'public');
    }

    $messages = [];

    // ── Gestion compte parent ──
    if ($request->boolean('creer_compte_parent')) {
        if ($eleve->parent_user_id) {
            // Compte existant : mise à jour
            $update = [];
            if ($request->filled('parent_nom'))   $update['name']  = $request->parent_nom;
            if ($request->filled('parent_email') && $request->parent_email !== $eleve->parentUser?->email) {
                $exists = User::where('email', $request->parent_email)->where('id','!=',$eleve->parent_user_id)->exists();
                if (!$exists) $update['email'] = $request->parent_email;
            }
            if ($request->filled('parent_password')) $update['password'] = Hash::make($request->parent_password);
            if (!empty($update)) $eleve->parentUser?->update($update);
        } else {
            // Nouveau compte parent
            if ($request->filled('parent_email') && !User::where('email',$request->parent_email)->exists()) {
                $mdp = $request->parent_password ?: Str::random(10);
                $parentUser = User::create([
                    'name'      => $request->parent_nom ?? $validated['nom_pere'] ?? 'Parent',
                    'email'     => $request->parent_email,
                    'password'  => Hash::make($mdp),
                    'telephone' => $validated['telephone_parent'] ?? null,
                    'actif'     => true,
                ]);
                $parentUser->assignRole('parent');
                $validated['parent_user_id'] = $parentUser->id;
                $messages[] = "Compte PARENT créé : {$request->parent_email} / {$mdp}";
            }
        }
    }

    // ── Gestion compte élève ──
    if ($request->boolean('creer_compte_eleve')) {
        if ($eleve->eleve_user_id) {
            $update = [];
            if ($request->filled('eleve_email') && $request->eleve_email !== $eleve->eleveUser?->email) {
                $exists = User::where('email', $request->eleve_email)->where('id','!=',$eleve->eleve_user_id)->exists();
                if (!$exists) $update['email'] = $request->eleve_email;
            }
            if ($request->filled('eleve_password')) $update['password'] = Hash::make($request->eleve_password);
            if (!empty($update)) $eleve->eleveUser?->update($update);
        } else {
            if ($request->filled('eleve_email') && !User::where('email',$request->eleve_email)->exists()) {
                $mdp = $request->eleve_password ?: Str::random(10);
                $eleveUser = User::create([
                    'name'     => $validated['prenom'].' '.$validated['nom'],
                    'email'    => $request->eleve_email,
                    'password' => Hash::make($mdp),
                    'actif'    => true,
                ]);
                $eleveUser->assignRole('eleve');
                $validated['eleve_user_id'] = $eleveUser->id;
                $messages[] = "Compte ÉLÈVE créé : {$request->eleve_email} / {$mdp}";
            }
        }
    }

    $eleve->update($validated);

    $msg = "Élève modifié avec succès.";
    if (!empty($messages)) $msg .= " — " . implode(' | ', $messages);

    return redirect()->route('admin.eleves.show', $eleve)->with('success', $msg);
}

    public function destroy(Eleve $eleve)
    {
        $eleve->delete();

        return redirect()->route('admin.eleves.index')
            ->with('success', 'Élève supprimé.');
    }
}