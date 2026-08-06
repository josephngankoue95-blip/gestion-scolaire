<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompteGenere;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ReinitialiserMotsDePasseManquants extends Command
{
    protected $signature = 'comptes:reinitialiser-mdp {--role=}';
    protected $description = 'Génère un nouveau mot de passe visible pour tous les comptes marqués "non disponible"';

    public function handle()
    {
        $query = CompteGenere::where('mot_de_passe', 'like', '%non disponible%');

        if ($this->option('role')) {
            $query->where('role', $this->option('role'));
        }

        $comptes = $query->get();
        $nb = 0;

        foreach ($comptes as $compte) {
            $user = User::find($compte->user_id);
            if (!$user) continue;

            $nouveauMdp = Str::random(10);
            $user->update(['password' => Hash::make($nouveauMdp)]);
            $compte->update(['mot_de_passe' => $nouveauMdp]);

            $this->line("✓ {$compte->nom} ({$compte->email}) → nouveau mot de passe : {$nouveauMdp}");
            $nb++;
        }

        $this->info("{$nb} compte(s) réinitialisé(s).");
    }
}