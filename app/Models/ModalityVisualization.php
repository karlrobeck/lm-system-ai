<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalityVisualization extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'question_index',
        'test_type',
        'question',
        'choices',
        'correct_answer',
        'image_prompt',
        'image_url',
    ];

    public function file()
    {
        return $this->belongsTo(Files::class);
    }
}