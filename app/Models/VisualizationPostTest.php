<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisualizationPostTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'question_index',
        'question',
        'choices',
        'answer',
        'image_prompt',
        'image_url',
        'test_type',
    ];

    public function file()
    {
        return $this->belongsTo(Files::class);
    }
}