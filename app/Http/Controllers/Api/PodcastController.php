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

    //  pour streamer un podcast avec support Range Requests et CORS
    public function stream(Request $request, $id)
    {
        $podcast = Podcast::findOrFail($id);

        if (!$podcast->fichier || !Storage::disk('public')->exists($podcast->fichier)) {
            return response()->json(['message' => 'Fichier non trouvé'], 404);
        }

        $path     = Storage::disk('public')->path($podcast->fichier);
        $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        $fileSize = filesize($path);

        $headers = [
            'Content-Type'                 => $mimeType,
            'Accept-Ranges'                => 'bytes',
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Range, Content-Type',
        ];

        // Gestion des Range Requests (nécessaire pour le seek vidéo dans le navigateur)
        if ($request->hasHeader('Range')) {
            $range = $request->header('Range');
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);

            $start  = (int) $matches[1];
            $end    = (isset($matches[2]) && $matches[2] !== '') ? (int) $matches[2] : $fileSize - 1;
            $length = $end - $start + 1;

            $headers['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
            $headers['Content-Length'] = $length;

            return response()->stream(function () use ($path, $start, $length) {
                $stream = fopen($path, 'rb');
                fseek($stream, $start);
                $remaining = $length;
                while (!feof($stream) && $remaining > 0) {
                    $chunk = min(8192, $remaining);
                    echo fread($stream, $chunk);
                    $remaining -= $chunk;
                    flush();
                }
                fclose($stream);
            }, 206, $headers);
        }

        $headers['Content-Length'] = $fileSize;

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 8192);
                flush();
            }
            fclose($stream);
        }, 200, $headers);
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
