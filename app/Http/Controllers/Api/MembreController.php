<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membre;
use Illuminate\Http\Request;

class MembreController extends Controller
{
    public function index()
    {
        return Membre::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'required|string',
            'nom' => 'required|string',
            'lien_photo' => 'nullable|url'
        ]);

        return Membre::create($data);
    }

    public function show($id)
    {
        return Membre::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $membre = Membre::findOrFail($id);

        $data = $request->validate([
            'prenom' => 'sometimes|string',
            'nom' => 'sometimes|string',
            'lien_photo' => 'nullable|url'
        ]);

        $membre->update($data);

        return $membre;
    }

    public function destroy($id)
    {
        Membre::destroy($id);
        return response()->json(['message' => 'Supprimé']);
    }
}
