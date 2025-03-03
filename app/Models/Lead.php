<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'leads';  // Spécifiez le nom de la table si nécessaire

    // Déclarez les champs que vous souhaitez autoriser pour l'assignation massive
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'csv_filename', 
        // Ajoutez tous les autres champs que vous souhaitez autoriser
    ];
}
