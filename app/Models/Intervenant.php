<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Evenement; 

class Intervenant extends Model
{
    protected $table = 'sn_intervenants';

    protected $fillable = [
        'prenom',
        'nom',
        'sexe'
    ];

    public function evenements()
    {
        return $this->belongsToMany(
            Evenement::class,
            'sn_evenement_intervenant',
            'intervenant',
            'evenement'
          )->withTimestamps();
    }
}
