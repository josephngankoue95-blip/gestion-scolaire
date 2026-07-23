<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZoneTransport;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class ZoneTransportController extends Controller
{
    public function index()
    {
        $annee = AnneeScolaire::getActive();
        $zones = ZoneTransport::where('annee_scolaire_id', $annee?->id)->orderBy('nom')->get();
        return view('admin.scolarite.transport.index', compact('zones', 'annee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:100',
            'quartiers' => 'nullable|string|max:500',
            'montant'   => 'required|numeric|min:0',
            'actif'     => 'nullable|boolean',
        ]);
        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;
        $validated['actif']             = $request->boolean('actif', true);
        ZoneTransport::create($validated);
        return back()->with('success', 'Zone créée.');
    }

    public function update(Request $request, ZoneTransport $zoneTransport)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:100',
            'quartiers' => 'nullable|string|max:500',
            'montant'   => 'required|numeric|min:0',
            'actif'     => 'nullable|boolean',
        ]);
        $validated['actif'] = $request->boolean('actif', true);
        $zoneTransport->update($validated);
        return back()->with('success', 'Zone mise à jour.');
    }

    public function destroy(ZoneTransport $zoneTransport)
    {
        $zoneTransport->delete();
        return back()->with('success', 'Zone supprimée.');
    }
}