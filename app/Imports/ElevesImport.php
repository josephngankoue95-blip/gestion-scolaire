<?php
// app/Imports/ElevesImport.php
namespace App\Imports;

use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;

class ElevesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected int $nbImportes = 0;

    public function model(array $row)
    {
        $classe = ClasseModel::where('nom', trim($row['classe'] ?? ''))
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->first();

        $this->nbImportes++;

        return new Eleve([
            'matricule'        => Eleve::genererMatricule(),
            'nom'              => trim($row['nom']),
            'prenom'           => trim($row['prenom']),
            'date_naissance'   => $this->parseDate($row['date_naissance'] ?? null),
            'lieu_naissance'   => $row['lieu_naissance'] ?? null,
            'sexe'             => strtoupper(substr($row['sexe'] ?? 'M', 0, 1)),
            'telephone_parent' => $row['telephone_parent'] ?? null,
            'adresse'          => $row['adresse'] ?? null,
            'classe_id'        => $classe?->id,
            'statut'           => 'actif',
        ]);
    }

    protected function parseDate($value): ?string
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'sexe'   => 'required',
            'classe' => 'required|string',
        ];
    }

    public function getNbImportes(): int
    {
        return $this->nbImportes;
    }
}