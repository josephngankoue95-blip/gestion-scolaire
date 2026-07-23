<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        Section::firstOrCreate(['code' => 'FR'], ['nom' => 'Francophone']);
        Section::firstOrCreate(['code' => 'ANG'], ['nom' => 'Anglophone']);
    }
}