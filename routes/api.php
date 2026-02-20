<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    IntervenantController,
    EvenementController,
    EvenementIntervenantController,
    CategorieController,
    DocumentController,
    MembreController,
    PodcastController
};

Route::post('/evenement-intervenants', [EvenementIntervenantController::class, 'store']);

Route::get('/evenement-intervenants', [EvenementIntervenantController::class, 'index']);

Route::get('/lastpodcasts', [PodcastController::class, 'lastPodcasts']);

Route::apiResource('intervenants', IntervenantController::class);
Route::apiResource('evenements', EvenementController::class);
Route::apiResource('categories', CategorieController::class);
Route::apiResource('documents', DocumentController::class);
Route::apiResource('membres', MembreController::class);
Route::apiResource('podcasts', PodcastController::class);


Route::get('evenement-intervenants', [EvenementIntervenantController::class, 'index']);


// Routes  pour les documents
Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

// Routes  pour les podcasts

Route::get('/podcasts/{id}/download', [PodcastController::class, 'download'])->name('podcasts.download');
Route::get('/podcasts/{id}/stream', [PodcastController::class, 'stream'])->name('podcasts.stream');
Route::get('/dashboard', function () {
    return response()->json([
        'intervenants' => \App\Models\Intervenant::all(),
        'evenements'   => \App\Models\Evenement::all(),
        'categories'   => \App\Models\Categorie::all(),
        'documents'    => \App\Models\Document::all(),
        'membres'      => \App\Models\Membre::all(),
        'podcasts'     => \App\Models\Podcast::all(),
    ]);
});
