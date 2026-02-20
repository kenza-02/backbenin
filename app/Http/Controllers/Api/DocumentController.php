<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        return Document::with('categorie')->get();
    }

    public function store(StoreDocumentRequest $request)
    {
        $validated = $request->validated();

        // Stocker le fichier
        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('documents', 'public');
            $validated['fichier'] = $filePath;
        }

        $document = Document::create($validated);
        return response()->json($document->load('categorie'), 201);
    }

    public function show($id)
    {
        return Document::with('categorie')->findOrFail($id);
    }

    public function update(UpdateDocumentRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $validated = $request->validated();

        // Stocker le nouveau fichier
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($document->fichier && Storage::disk('public')->exists($document->fichier)) {
                Storage::disk('public')->delete($document->fichier);
            }
            $filePath = $request->file('fichier')->store('documents', 'public');
            $validated['fichier'] = $filePath;
        }

        $document->update($validated);
        return response()->json($document->load('categorie'), 200);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Supprimer le fichier stocké
        if ($document->fichier && Storage::disk('public')->exists($document->fichier)) {
            Storage::disk('public')->delete($document->fichier);
        }

        $document->delete();
        return response()->json(['message' => 'Document supprimé avec succès'], 200);
    }

    //  pour télécharger un document
    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return response()->json(['message' => 'Fichier non trouvé'], 404);
        }

        return Storage::disk('public')->download($document->fichier);
    }
}
