<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CompteGenere;
use App\Models\Eleve;

class RattraperComptesGeneres extends Command
{
    protected $signature = 'comptes:rattraper';
    protected $description = 'Peuple comptes_generes pour tous les utilisateurs existants non journalisés';

    public function handle()
    {
        $users = User::with('roles')->get();
        $nb = 0;

        foreach ($users as $user) {
            $role = $user->roles->first()?->name;
            if (!$role) continue;

            $dejaJournalise = CompteGenere::where('user_id', $user->id)->exists();
            if ($dejaJournalise) continue;

            $eleveLie = null;
            if ($role === 'parent') {
                $eleveLie = Eleve::where('parent_user_id', $user->id)->pluck('nom')->implode(', ');
            }

            CompteGenere::create([
                'user_id'      => $user->id,
                'nom'          => $user->name,
                'email'        => $user->email,
                'mot_de_passe' => '(non disponible — créé avant activation du suivi)',
                'role'         => $role,
                'eleve_lie'    => $eleveLie,
            ]);
            $nb++;
        }

        $this->info("{$nb} compte(s) rattrapé(s) dans le journal.");
    }
}