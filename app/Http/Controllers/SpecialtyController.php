<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $specialties = Specialty::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10);
        return view('specialties.index', compact('specialties'));
    }

    public function create() { return view('specialties.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:specialties', 'description' => 'nullable']);
        Specialty::create($validated);
        return redirect()->route('specialties.index')->with('success', 'Specialty created.');
    }

    public function edit(Specialty $specialty) { return view('specialties.edit', compact('specialty')); }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate(['name' => 'required|unique:specialties,name,'.$specialty->id, 'description' => 'nullable', 'is_active' => 'boolean']);
        $specialty->update($validated);
        return redirect()->route('specialties.index')->with('success', 'Specialty updated.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('specialties.index')->with('success', 'Specialty deleted.');
    }
}