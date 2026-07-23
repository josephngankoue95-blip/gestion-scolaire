<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Evenement::orderByDesc('date_evenement')->paginate(15);
        return view('admin.evenements.index', compact('evenements'));
    }

    public function create() { return view('admin.evenements.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:3072',
            'date_evenement' => 'required|date',
            'categorie' => 'required|in:secondaire,universite,general',
            'publie' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);
        $validated['publie'] = $request->boolean('publie', true);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('evenements', 'public');
        }
        Evenement::create($validated);
        return redirect()->route('admin.evenements.index')->with('success', 'Événement ajouté.');
    }

    public function edit(Evenement $evenement) { return view('admin.evenements.edit', compact('evenement')); }

    public function update(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:3072',
            'date_evenement' => 'required|date',
            'categorie' => 'required|in:secondaire,universite,general',
            'publie' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);
        $validated['publie'] = $request->boolean('publie', true);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('evenements', 'public');
        }
        $evenement->update($validated);
        return redirect()->route('admin.evenements.index')->with('success', 'Événement modifié.');
    }

    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return back()->with('success', 'Événement supprimé.');
    }
}