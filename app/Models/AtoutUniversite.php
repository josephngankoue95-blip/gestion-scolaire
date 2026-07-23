<?php
// app/Models/AtoutUniversite.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtoutUniversite extends Model
{
    protected $table = 'atouts_universite';
    protected $fillable = ['libelle', 'icone', 'ordre'];
}