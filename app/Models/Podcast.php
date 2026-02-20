<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $table = 'sn_podcasts';

    protected $fillable = [
        'libelle',
        'description',
        'membre_id',
        'fichier',
        'categorie_id'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'membre_id');
    }
}
