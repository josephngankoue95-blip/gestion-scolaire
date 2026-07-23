<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnneeScolaire;
use App\Models\Trimestre;
use App\Models\Sequence;

class TrimestreSeeder extends Seeder
{
    public function run(): void
    {
        $annee = AnneeScolaire::getActive();
        if (!$annee) return;

        $trimestresData = [
            ['nom' => 'Trimestre 1', 'numero' => 1],
            ['nom' => 'Trimestre 2', 'numero' => 2],
            ['nom' => 'Trimestre 3', 'numero' => 3],
        ];

        foreach ($trimestresData as $data) {
            $trimestre = Trimestre::firstOrCreate(
                ['numero' => $data['numero'], 'annee_scolaire_id' => $annee->id],
                ['nom' => $data['nom']]
            );

            // 2 séquences par trimestre
            for ($i = 1; $i <= 2; $i++) {
                $numeroGlobal = ($data['numero'] - 1) * 2 + $i;
                Sequence::firstOrCreate(
                    ['numero' => $numeroGlobal, 'trimestre_id' => $trimestre->id],
                    ['nom' => "Séquence {$numeroGlobal}"]
                );
            }
        }
    }
}