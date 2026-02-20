<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EvenementIntervenantController extends Controller
{
    public function index()
    {
        $data = DB::table('sn_evenement_intervenant')
            ->join(
                'sn_evenements',
                'sn_evenements.id',
                '=',
                'sn_evenement_intervenant.evenement'
            )
            ->join(
                'sn_intervenants',
                'sn_intervenants.id',
                '=',
                'sn_evenement_intervenant.intervenant'
            )
            ->select(
                'sn_evenement_intervenant.id',
                'sn_evenements.id as evenement_id',
                'sn_evenements.libelle as evenement_libelle',
                'sn_intervenants.id as intervenant_id',
                'sn_intervenants.prenom as intervenant_prenom',
                'sn_intervenants.nom as intervenant_nom',
                'sn_evenement_intervenant.created_at',
                'sn_evenement_intervenant.updated_at'
            )
            ->get();

        return response()->json($data);
    }
    public function store(Request $request)
{
    $request->validate([
        'evenement_id' => 'required|exists:sn_evenements,id',
        'intervenant_ids' => 'required|array',
        'intervenant_ids.*' => 'exists:sn_intervenants,id',
    ]);

    $evenementId = $request->evenement_id;
    $intervenantIds = $request->intervenant_ids;

    foreach ($intervenantIds as $id) {
        DB::table('sn_evenement_intervenant')->insert([
            'evenement' => $evenementId,
            'intervenant' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return response()->json(['message' => 'Relations créées avec succès'], 201);
}

}
