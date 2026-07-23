<?php
namespace App\Http\Controllers\Surveillant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\ClasseModel;
use App\Models\Eleve;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Absence::with('eleve', 'classe', 'signalePar');

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date_absence', $request->date);
        }

        $absences = $query->latest('date_absence')->paginate(20);

        $classes = ClasseModel::where('annee_scolaire_id', AnneeScolaire::getActive()?->id)->get();

        return view('surveillant.absences.index', compact('absences', 'classes'));
    }

    public function create(ClasseModel $classe)
    {
        // ✅ PLUS D’INSCRIPTIONS → on prend directement les élèves de la classe
        $eleves = $classe->eleves()->orderBy('nom')->get();

        return view('surveillant.absences.create', compact('classe', 'eleves'));
    }

    public function store(Request $request, ClasseModel $classe)
    {
        $validated = $request->validate([
            'date_absence' => 'required|date',
            'absences' => 'required|array',
            'absences.*.eleve_id' => 'required|exists:eleves,id',
            'absences.*.present' => 'nullable|boolean',
            'absences.*.type' => 'nullable|in:absence,retard',
            'absences.*.motif' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $classe) {

            foreach ($validated['absences'] as $item) {

                // on enregistre uniquement absents / retards
                if (empty($item['present'])) {

                    Absence::create([
                        'eleve_id' => $item['eleve_id'],
                        'classe_id' => $classe->id,
                        'date_absence' => $validated['date_absence'],
                        'type' => $item['type'] ?? 'absence',
                        'motif' => $item['motif'] ?? null,
                        'signale_par' => Auth::id(),
                    ]);
                }
            }
        });

        return redirect()->route('surveillant.absences.index')
            ->with('success', 'Absences enregistrées avec succès.');
    }

    public function justifier(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'motif' => 'required|string|max:255',
        ]);

        $absence->update([
            'justifiee' => true,
            'motif' => $validated['motif'],
        ]);

        return back()->with('success', 'Absence justifiée.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();
        return back()->with('success', 'Absence supprimée.');
    }
}