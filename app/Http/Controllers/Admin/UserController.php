<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $annee = AnneeScolaire::getActive();

        $roles = Role::all();

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with(['section', 'niveau'])
            ->orderBy('niveau_id')
            ->orderBy('nom')
            ->get();

        return view('admin.users.create', compact('roles', 'classes'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:150',
        'email'         => 'required|email|unique:users,email',
        'telephone'     => 'nullable|string|max:20',
        'password'      => 'required|string|min:6|confirmed',
        'role'          => 'required|exists:roles,name',
        'actif'         => 'nullable|boolean',
        'eleves_ids'    => 'nullable|array',
        'eleves_ids.*'  => 'exists:eleves,id',
    ]);

    $user = User::create([
        'name'      => $validated['name'],
        'email'     => $validated['email'],
        'telephone' => $validated['telephone'] ?? null,
        'password'  => Hash::make($validated['password']),
        'actif'     => $request->boolean('actif', true),
    ]);

    $user->assignRole($validated['role']);

    if ($validated['role'] === 'parent' && !empty($validated['eleves_ids'])) {
        Eleve::whereIn('id', $validated['eleves_ids'])->update(['parent_user_id' => $user->id]);
    }

    // ✅ Journalisation systématique, quel que soit le rôle
    \App\Models\CompteGenere::create([
        'user_id'      => $user->id,
        'nom'          => $user->name,
        'email'        => $user->email,
        'mot_de_passe' => $validated['password'], // avant hash
        'role'         => $validated['role'],
        'eleve_lie'    => $validated['role'] === 'parent' && !empty($validated['eleves_ids'])
            ? Eleve::whereIn('id', $validated['eleves_ids'])->pluck('nom')->implode(', ')
            : null,
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
}

    public function elevesByClasse(Request $request)
{
    $request->validate([
        'classe_id' => 'required|exists:classes,id',
    ]);

    $eleves = Eleve::where('classe_id', $request->classe_id)
        ->orderBy('nom')
        ->orderBy('prenom')
        ->get(['id', 'nom', 'prenom', 'matricule']);

    return response()->json($eleves);
}

    public function show(User $user)
    {
        $user->load('roles');

        $eleves = collect();

        if ($user->hasRole('parent')) {
            $eleves = Eleve::where('parent_user_id', $user->id)
                ->with([
                    'scolariteActive.classe.section',
                    'scolariteActive.classe.niveau'
                ])
                ->get();
        }

        return view('admin.users.show', compact('user', 'eleves'));
    }

    public function edit(User $user)
{
    $annee = AnneeScolaire::getActive();
    $roles = Role::all();

    $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
        ->with(['section', 'niveau'])
        ->orderBy('niveau_id')
        ->orderBy('nom')
        ->get();

    $elevesLies = Eleve::where('parent_user_id', $user->id)->pluck('id')->toArray();

    $classeIdSelectionnee = null;
    if (!empty($elevesLies)) {
        $classeIdSelectionnee = Eleve::whereIn('id', $elevesLies)->value('classe_id');
    }

    return view('admin.users.edit', compact(
        'user',
        'roles',
        'classes',
        'elevesLies',
        'classeIdSelectionnee'
    ));
}

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:150',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'telephone'       => 'nullable|string|max:20',
            'password'        => 'nullable|string|min:6|confirmed',
            'role'            => 'required|exists:roles,name',
            'actif'           => 'nullable|boolean',
            'eleves_ids'      => 'nullable|array',
            'eleves_ids.*'    => 'exists:eleves,id',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'actif'     => $request->boolean('actif', true),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        $user->syncRoles([$validated['role']]);

        if ($validated['role'] === 'parent') {

            Eleve::where('parent_user_id', $user->id)
                ->update([
                    'parent_user_id' => null
                ]);

            if (!empty($validated['eleves_ids'])) {
                Eleve::whereIn('id', $validated['eleves_ids'])
                    ->update([
                        'parent_user_id' => $user->id
                    ]);
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Impossible de supprimer votre propre compte.'
            );
        }

        Eleve::where('parent_user_id', $user->id)
            ->update([
                'parent_user_id' => null
            ]);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}