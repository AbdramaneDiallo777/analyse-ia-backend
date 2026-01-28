<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'sentiment',
        'score',
        'topics',
    ];

    protected $casts = [
        'topics' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}

