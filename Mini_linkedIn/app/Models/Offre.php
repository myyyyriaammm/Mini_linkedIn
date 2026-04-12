<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offre extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'localisation',
        'type',
        'actif',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

}
