<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Champs qu'on a le droit de remplir
    protected $fillable = [
        'user_id',
        'content',
        'sentiment',
        'score',
        'topics',
    ];

    // Conversion automatique : La base stocke du JSON, mais Laravel nous donne un Tableau
    protected $casts = [
        'topics' => 'array',
    ];

    // Lien vers l'utilisateur (un avis appartient à un User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}