<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PodcastController extends Controller
{
    public function index()
    {
        return Podcast::with(['categorie', 'membre'])->get();
    }

    public function store(StorePodcastRequest $request)
    {
        $validated = $request->validated();

        // Stocker le fichier audio
        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('podcasts', 'public');
            $validated['fichier'] = $filePath;
        }

        $podcast = Podcast::create($validated);
        return response()->json($podcast->load(['categorie', 'membre']), 201);
    }

    public function show($id)
    {
        return Podcast::with(['categorie', 'membre'])->findOrFail($id);
    }

    public function update(UpdatePodcastRequest $request, $id)
    {
        $podcast = Podcast::findOrFail($id);
        $validated = $request->validated();

        // Stocker le nouveau fichier audio 
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($podcast->fichier && Storage::disk('public')->exists($podcast->fichier)) {
                Storage::disk('public')->delete($podcast->fichier);
            }
            $filePath = $request->file('fichier')->store('podcasts', 'public');
            $validated['fichier'] = $filePath;
        }

        $podcast->update($validated);
        return response()->json($podcast->load(['categorie', 'membre']), 200);
    }

    public function destroy($id)
    {
        $podcast = Podcast::findOrFail($id);

        // Supprimer le fichier audio stocké
        if ($podcast->fichier && Storage::disk('public')->exists($podcast->fichier)) {
            Storage::disk('public')->delete($podcast->fichier);
        }

        $podcast->delete();
        return response()->json(['message' => 'Podcast supprimé avec succès'], 200);
    }

    //  pour télécharger/streamer un podcast
    public function download($id)
    {
        $podcast = Podcast::findOrFail($id);

        if (!$podcast->fichier || !Storage::disk('public')->exists($podcast->fichier)) {
            return response()->json(['message' => 'Fichier audio non trouvé'], 404);
        }

        return Storage::disk('public')->download($podcast->fichier);
    }

    //  pour streamer un podcast 
    public function stream($id)
    {
        $podcast = Podcast::findOrFail($id);

        if (!$podcast->fichier || !Storage::disk('public')->exists($podcast->fichier)) {
            return response()->json(['message' => 'Fichier audio non trouvé'], 404);
        }

        return response()->file(
            Storage::disk('public')->path($podcast->fichier),
            ['Content-Type' => 'audio/mpeg']
        );
    }
    //affichage de 8 podcasts
    public function lastPodcasts()
{
    return \App\Models\Podcast::with(['categorie', 'membre'])
        ->orderBy('created_at', 'desc')
        ->limit(8)
        ->get();
}

}
