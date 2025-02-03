<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    /** @use HasFactory<\Database\Factories\ScoresFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'correct',
        'test_type',
        'modality',
        'total',
        'is_passed',
        'rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function file() {
        return $this->belongsTo(Files::class, 'file_id');
    }
}
