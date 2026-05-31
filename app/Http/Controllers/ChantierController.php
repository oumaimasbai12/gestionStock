<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use Illuminate\Http\Request;

class ChantierController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()?->hasRole('admin')) {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $chantiers = Chantier::withCount(['exits', 'entries', 'users'])
            ->orderBy('name')
            ->paginate(10);

        return view('chantiers.index', compact('chantiers'));
    }

    public function create()
    {
        return view('chantiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:chantiers,name',
        ]);

        Chantier::create($request->only('name'));

        return redirect()->route('chantiers.index')->with('success', 'Chantier créé avec succès.');
    }

    public function edit(Chantier $chantier)
    {
        return view('chantiers.edit', compact('chantier'));
    }

    public function update(Request $request, Chantier $chantier)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:chantiers,name,' . $chantier->id,
        ]);

        $chantier->update($request->only('name'));

        return redirect()->route('chantiers.index')->with('success', 'Chantier mis à jour avec succès.');
    }

    public function destroy(Chantier $chantier)
    {
        if ($chantier->users()->exists()) {
            return redirect()->route('chantiers.index')
                ->withErrors(['name' => 'Impossible de supprimer : des responsables de chantier y sont assignés.']);
        }

        $chantier->delete();

        return redirect()->route('chantiers.index')->with('success', 'Chantier supprimé avec succès.');
    }
}
