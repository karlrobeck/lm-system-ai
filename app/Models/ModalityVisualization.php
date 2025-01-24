<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ModalityVisualization extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'image_link',
        'choices',
        'question_index',
        'correct_answer',
        'file_id',
    ];

    public function file(): HasOne {
        return $this->hasOne(Files::class,'id','file_id');
    }

}
