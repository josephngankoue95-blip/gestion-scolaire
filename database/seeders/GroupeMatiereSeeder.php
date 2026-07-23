<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\GroupeMatiere;
use App\Models\Matiere;

class GroupeMatiereSeeder extends Seeder
{
    public function run(): void
    {
        $fr = Section::where('code', 'FR')->first();

        $matieres = [
            ['nom' => 'Mathématiques', 'code' => 'MATH-FR', 'coefficient' => 4],
            ['nom' => 'Français', 'code' => 'FR-FR', 'coefficient' => 4],
            ['nom' => 'Physique-Chimie', 'code' => 'PC-FR', 'coefficient' => 3],
            ['nom' => 'Histoire-Géographie', 'code' => 'HG-FR', 'coefficient' => 2],
            ['nom' => 'Anglais', 'code' => 'ANG-FR', 'coefficient' => 2],
        ];

        foreach ($matieres as $m) {
            Matiere::firstOrCreate(['code' => $m['code']], [...$m, 'section_id' => $fr->id]);
        }

        $groupe = GroupeMatiere::firstOrCreate(
            ['code' => 'TC-FR'],
            ['nom' => 'Tronc commun', 'section_id' => $fr->id]
        );

        $groupe->matieres()->sync(Matiere::where('section_id', $fr->id)->pluck('id'));
    }
}