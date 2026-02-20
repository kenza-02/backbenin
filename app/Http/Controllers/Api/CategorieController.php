<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        return Categorie::all();
    }

    public function store(Request $request)
    {
        return Categorie::create($request->all());
    }

    public function show($id)
    {
        return Categorie::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->update($request->all());
        return $categorie;
    }

    public function destroy($id)
    {
        Categorie::destroy($id);
        return response()->json(['message' => 'Supprimé']);
    }
}
