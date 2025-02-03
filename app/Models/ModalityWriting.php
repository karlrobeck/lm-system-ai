<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalityWriting extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'question',
        'context_answer',
        'question_index',
        'test_type',
    ];
}
