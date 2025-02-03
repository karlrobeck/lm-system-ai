<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalityReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'question',
        'choices',
        'question_index',
        'correct_answer',
        'test_type',
    ];
}
