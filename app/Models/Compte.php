// app/Models/Compte.php

<?php

namespace App\Models; // Le namespace doit être App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model // Le Modèle hérite de Eloquent\Model
{
    use HasFactory;

    // Ici, vous ajouterez $fillable, les relations, etc.
}