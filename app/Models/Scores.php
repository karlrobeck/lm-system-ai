<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    /** @use HasFactory<\Database\Factories\ScoresFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
