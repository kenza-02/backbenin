<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Intervenant; 
$evenement->intervenants()->attach($intervenantIds); 

class Evenement extends Model
{
    protected $table = 'sn_evenements';

    protected $fillable = [
        'libelle',
        'description',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'type',
        'lieu',
        'lien',
        'image'
    ];

    public function intervenants()
    {
        return $this->belongsToMany(
            Intervenant::class,
            'sn_evenement_intervenant', 
            'evenement', 
            'intervenant' 
        );
    }
    
}
