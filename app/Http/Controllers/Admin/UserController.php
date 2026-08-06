<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeScolaire;
use App\Models\ClasseModel;
use App\Models\CompteGenere;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact(
            'users',
            'roles'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        $anneeActive = AnneeScolaire::getActive();

        $classes = ClasseModel::query()
            ->when(
                $anneeActive,
                fn ($query) => $query->where(
                    'annee_scolaire_id',
                    $anneeActive->id
                )
            )
            ->with(['section', 'niveau'])
            ->orderBy('nom')
            ->get();

        return view('admin.users.create', compact(
            'roles',
            'classes'
        ));
    }

    /**
     * Enregistrer un nouvel utilisateur.
     *
     * Le formulaire doit envoyer :
     * - role
     * - eleves_ids[]
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max: evidente',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],

            'classe_id' => [
                'nullable',
                'exists:classes,id',
            ],

            'eleves_ids' => [
                'nullable',
                'array',
            ],

            'eleves_ids.*' => [
                'integer',
                'exists:eleves,id',
            ],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'password' => Hash::make($validated['password']),
                'actif' => $request->boolean('actif'),
            ]);

            // syncRoles remplace les rôles existants par celui envoyé.
            $user->syncRoles([$validated['role']]);

            $elevesIds = $validated['eleves_ids'] ?? [];

            if (
                $validated['role'] === 'parent'
                && !empty($elevesIds)
            ) {
                Eleve::whereIn('id', $elevesIds)
                    ->update([
                        'parent_user_id' => $user->id,
                    ]);
            }

            CompteGenere::create([
                'user_id' => $user->id,
                'nom' => $user->name,
                'email' => $user->email,
                'mot_de_passe' => $validated['password'],
                'role' => $validated['role'],
                'eleve_lie' => $validated['role'] === 'parent'
                    && !empty($elevesIds)
                        ? Eleve::whereIn('id', $elevesIds)
                            ->pluck('nom')
                            ->implode(', ')
                        : null,
            ]);
        });

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Utilisateur créé avec succès.'
            );
    }

    /**
     * Retourner les élèves d'une classe en JSON.
     */
    public function elevesByClasse(Request $request)
    {
        $validated = $request->validate([
            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],
        ]);

        $eleves = Eleve::query()
            ->where('classe_id', $validated['classe_id'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get([
                'id',
                'nom',
                'prenom',
                'matricule',
            ]);

        return response()->json($eleves);
    }

    /**
     * Afficher le détail d'un utilisateur.
     */
    public function show(User $user)
    {
        $user->load('roles');

        $eleves = collect();

        if ($user->hasRole('parent')) {
            $eleves = Eleve::query()
                ->where('parent_user_id', $user->id)
                ->with([
                    'scolariteActive.classe.section',
                    'scolariteActive.classe.niveau',
                ])
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();
        }

        return view('admin.users.show', compact(
            'user',
            'eleves'
        ));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(User $user)
    {
        $user->load('roles');

        $roles = Role::orderBy('name')->get();

        $anneeActive = AnneeScolaire::getActive();

        $classes = ClasseModel::query()
            ->when(
                $anneeActive,
                fn ($query) => $query->where(
                    'annee_scolaire_id',
                    $anneeActive->id
                )
            )
            ->with(['section', 'niveau'])
            ->orderBy('nom')
            ->get();

        /*
         * Important :
         * Cette variable est bien appelée $elevesLies,
         * exactement comme dans la vue Blade.
         */
        $elevesLies = Eleve::query()
            ->where('parent_user_id', $user->id)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->toArray();

        /*
         * On récupère la classe du premier élève lié.
         * Le formulaire actuel permet de sélectionner une seule classe.
         */
        $classeId = Eleve::query()
            ->where('parent_user_id', $user->id)
            ->value('classe_id');

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'classes',
            'elevesLies',
            'classeId'
        ));
    }

    /**
     * Mettre à jour un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],

            'classe_id' => [
                'nullable',
                'exists:classes,id',
            ],

            'eleves_ids' => [
                'nullable',
                'array',
            ],

            'eleves_ids.*' => [
                'integer',
                'exists:eleves,id',
            ],
        ]);

        DB::transaction(function () use ($request, $validated, $user) {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'actif' => $request->boolean('actif'),
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make(
                    $validated['password']
                );
            }

            $user->update($data);

            // Un seul rôle est conservé.
            $user->syncRoles([$validated['role']]);

            /*
             * On supprime toujours les anciens liens avant
             * d'enregistrer les nouveaux.
             */
            Eleve::where('parent_user_id', $user->id)
                ->update([
                    'parent_user_id' => null,
                ]);

            $elevesIds = $validated['eleves_ids'] ?? [];

            if (
                $validated['role'] === 'parent'
                && !empty($elevesIds)
            ) {
                Eleve::whereIn('id', $elevesIds)
                    ->update([
                        'parent_user_id' => $user->id,
                    ]);
            }

            /*
             * Mise à jour facultative de CompteGenere.
             * Utilisez updateOrCreate pour éviter les doublons.
             */
            CompteGenere::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'nom' => $user->name,
                    'email' => $user->email,
                    'role' => $validated['role'],
                    'eleve_lie' => $validated['role'] === 'parent'
                        && !empty($elevesIds)
                            ? Eleve::whereIn('id', $elevesIds)
                                ->pluck('nom')
                                ->implode(', ')
                            : null,
                ]
            );
        });

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Utilisateur modifié avec succès.'
            );
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Impossible de supprimer votre propre compte.'
            );
        }

        DB::transaction(function () use ($user) {
            Eleve::where('parent_user_id', $user->id)
                ->update([
                    'parent_user_id' => null,
                ]);

            CompteGenere::where('user_id', $user->id)->delete();

            $user->delete();
        });

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }
}