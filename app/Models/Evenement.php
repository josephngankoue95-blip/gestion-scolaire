<?php
// app/Models/Evenement.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $fillable = ['titre', 'description', 'image', 'date_evenement', 'categorie', 'publie', 'ordre'];
    protected $casts = ['date_evenement' => 'date'];
}