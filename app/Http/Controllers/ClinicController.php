<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $clinics = Clinic::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10);
        return view('clinics.index', compact('clinics'));
    }

    public function create() { return view('clinics.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:clinics', 'description' => 'nullable']);
        Clinic::create($validated);
        return redirect()->route('clinics.index')->with('success', 'Clinic created.');
    }

    public function edit(Clinic $clinic) { return view('clinics.edit', compact('clinic')); }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate(['name' => 'required|unique:clinics,name,'.$clinic->id, 'description' => 'nullable', 'is_active' => 'boolean']);
        $clinic->update($validated);
        return redirect()->route('clinics.index')->with('success', 'Clinic updated.');
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return redirect()->route('clinics.index')->with('success', 'Clinic deleted.');
    }
}