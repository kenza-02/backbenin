<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvenementRequest;
use App\Http\Requests\UpdateEvenementRequest;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvenementController extends Controller
{
    public function index()
    {
        return Evenement::with('intervenants')->get();
    }

    public function store(StoreEvenementRequest $request)
    {
        $validated = $request->validated();

        // Stocker l'image si fournie
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('evenements', 'public');
            $validated['image'] = $imagePath;
        }

        $evenement = Evenement::create($validated);

        if ($request->intervenants) {
            $evenement->intervenants()->attach($request->intervenants);
        }

        return response()->json($evenement->load('intervenants'), 201);
    }

    public function show($id)
    {
        return Evenement::with('intervenants')->findOrFail($id);
    }

    public function update(UpdateEvenementRequest $request, $id)
    {
        $evenement = Evenement::findOrFail($id);
        $validated = $request->validated();

        // Stocker la nouvelle image si fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
                Storage::disk('public')->delete($evenement->image);
            }
            $imagePath = $request->file('image')->store('evenements', 'public');
            $validated['image'] = $imagePath;
        }

        $evenement->update($validated);

        if ($request->intervenants) {
            $evenement->intervenants()->sync($request->intervenants);
        }

        return response()->json($evenement->load('intervenants'), 200);
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);

        // Supprimer l'image stockée
        if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
            Storage::disk('public')->delete($evenement->image);
        }

        $evenement->delete();
        return response()->json(['message' => 'Événement supprimé avec succès'], 200);
    }
}
