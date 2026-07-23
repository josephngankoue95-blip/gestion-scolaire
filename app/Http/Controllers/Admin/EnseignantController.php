<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {

        $query = Enseignant::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('matricule', 'like', "%{$search}%");
        }

        $enseignants = $query->latest()->paginate(5);

        return view('admin.enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        return view('admin.enseignants.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'      => 'required|string|max:150',
        'email'     => 'required|email|unique:users,email',
        'telephone' => 'nullable|string|max:20',
        'matricule' => 'nullable|string|max:30',
        // ... autres champs enseignant
    ]);

    $motDePasse = \Illuminate\Support\Str::random(10);

    $user = \App\Models\User::create([
        'name'      => $validated['name'],
        'email'     => $validated['email'],
        'telephone' => $validated['telephone'] ?? null,
        'password'  => \Illuminate\Support\Facades\Hash::make($motDePasse),
        'actif'     => true,
    ]);
    $user->assignRole('enseignant');

    $enseignant = \App\Models\Enseignant::create([
        'user_id'   => $user->id,
        'matricule' => $validated['matricule'] ?? \App\Models\Enseignant::genererMatricule(),
        'statut'    => 'actif',
    ]);

    // ✅ Journalisation pour l'export
    \App\Models\CompteGenere::create([
        'user_id'      => $user->id,
        'nom'          => $user->name,
        'email'        => $user->email,
        'mot_de_passe' => $motDePasse,
        'role'         => 'enseignant',
        'eleve_lie'    => null,
    ]);

    return redirect()->route('admin.enseignants.index')
        ->with('success', "Enseignant créé. Identifiant : {$user->email} / Mot de passe : {$motDePasse}");
}

    public function show(Enseignant $enseignant)
    {
        $enseignant->load('user', 'affectationsAnneeActive.matiere', 'affectationsAnneeActive.classe');
        return view('admin.enseignants.show', compact('enseignant'));
    }

    public function edit(Enseignant $enseignant)
    {
        $enseignant->load('user');
        return view('admin.enseignants.edit', compact('enseignant'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $enseignant->user_id,
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
            'diplome' => 'nullable|string|max:100',
            'statut' => 'required|in:actif,inactif',
        ]);

        $enseignant->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
        ]);

        $enseignant->update([
            'specialite' => $validated['specialite'] ?? null,
            'diplome' => $validated['diplome'] ?? null,
            'statut' => $validated['statut'],
        ]);

        return redirect()->route('admin.enseignants.index', $enseignant)
            ->with('success', 'Enseignant modifié avec succès.');
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->user->delete(); // cascade supprime l'enseignant
        return redirect()->route('admin.enseignants.index')->with('success', 'Enseignant supprimé.');
    }
}