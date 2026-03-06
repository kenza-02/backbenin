<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EvenementIntervenant extends Pivot
{
    protected $table = 'sn_evenement_intervenant';

    protected $fillable = [
        'evenement_id',
        'intervenant_id',
    ];
}
