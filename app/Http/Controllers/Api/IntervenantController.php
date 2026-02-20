<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Intervenant;
use Illuminate\Http\Request;

class IntervenantController extends Controller
{
    public function index()
    {
        return Intervenant::with('evenements')->get();
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'prenom' => 'required|string',
        'nom' => 'required|string',
        'sexe' => 'nullable|string',
        'evenements' => 'nullable|array', // ids des événements
        'evenements.*' => 'integer|exists:sn_evenements,id',
    ]);

    $intervenant = Intervenant::create($data);

    if (!empty($data['evenements'])) {
        $intervenant->evenements()->attach($data['evenements']);
    }

    return response()->json($intervenant->load('evenements'), 201);
}


    public function show($id)
    {
        return Intervenant::with('evenements')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $intervenant = Intervenant::findOrFail($id);
        $intervenant->update($request->all());
        return $intervenant;
    }

    public function destroy($id)
    {
        Intervenant::destroy($id);
        return response()->json(['message' => 'Supprimé']);
    }
}
