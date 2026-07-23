<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Niveau;
use App\Models\Section;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        $fr  = Section::where('code', 'FR')->first();
        $ang = Section::where('code', 'ANG')->first();

        $niveauxFr = [
            ['nom' => '6ème',      'nom_en' => null, 'code' => '6EME', 'ordre' => 1, 'est_terminale' => false],
            ['nom' => '5ème',      'nom_en' => null, 'code' => '5EME', 'ordre' => 2, 'est_terminale' => false],
            ['nom' => '4ème',      'nom_en' => null, 'code' => '4EME', 'ordre' => 3, 'est_terminale' => false],
            ['nom' => '3ème',      'nom_en' => null, 'code' => '3EME', 'ordre' => 4, 'est_terminale' => true],  // fin 1er cycle
            ['nom' => 'Seconde',   'nom_en' => null, 'code' => 'SEC',  'ordre' => 5, 'est_terminale' => false],
            ['nom' => 'Première',  'nom_en' => null, 'code' => '1ERE', 'ordre' => 6, 'est_terminale' => false],
            ['nom' => 'Terminale', 'nom_en' => null, 'code' => 'TERM', 'ordre' => 7, 'est_terminale' => true],  // fin 2nd cycle
        ];

        $niveauxAng = [
            ['nom' => 'Form 1',      'nom_en' => 'Form 1',      'code' => 'F1',  'ordre' => 1, 'est_terminale' => false],
            ['nom' => 'Form 2',      'nom_en' => 'Form 2',      'code' => 'F2',  'ordre' => 2, 'est_terminale' => false],
            ['nom' => 'Form 3',      'nom_en' => 'Form 3',      'code' => 'F3',  'ordre' => 3, 'est_terminale' => false],
            ['nom' => 'Form 4',      'nom_en' => 'Form 4',      'code' => 'F4',  'ordre' => 4, 'est_terminale' => false],
            ['nom' => 'Form 5',      'nom_en' => 'Form 5',      'code' => 'F5',  'ordre' => 5, 'est_terminale' => true],  // fin O-Level
            ['nom' => 'Lower Sixth', 'nom_en' => 'Lower Sixth', 'code' => 'LS',  'ordre' => 6, 'est_terminale' => false],
            ['nom' => 'Upper Sixth', 'nom_en' => 'Upper Sixth', 'code' => 'US',  'ordre' => 7, 'est_terminale' => true],  // fin A-Level
        ];

        foreach ($niveauxFr as $n) {
            Niveau::updateOrCreate(
                ['code' => $n['code'], 'section_id' => $fr->id],
                [...$n, 'section_id' => $fr->id]
            );
        }

        foreach ($niveauxAng as $n) {
            Niveau::updateOrCreate(
                ['code' => $n['code'], 'section_id' => $ang->id],
                [...$n, 'section_id' => $ang->id]
            );
        }
    }
}