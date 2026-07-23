<?php
// app/Models/PartenaireUniversite.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartenaireUniversite extends Model
{
    protected $table = 'partenaires_universite';
    protected $fillable = ['nom', 'logo', 'pays', 'ordre'];
}