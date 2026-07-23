<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'telephone', 'photo', 'actif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function enseignant()
{
    return $this->hasOne(Enseignant::class);
}

public function section()
{
    return $this->belongsTo(Section::class);
}

public function niveau()
{
    return $this->belongsTo(Niveau::class);
}

public function classe()
{
    return $this->belongsTo(ClasseModel::class);
}

public function anneeScolaire()
{
    return $this->belongsTo(AnneeScolaire::class);
}

public function eleves()
{
    return $this->hasMany(Eleve::class, 'parent_user_id');
}

public function parent()
{
    return $this->belongsTo(User::class, 'parent_user_id');
}

public function scolariteActive()
{
    return $this->hasOne(Scolarite::class)
        ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id);
} 
}