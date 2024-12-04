<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalityVisualization extends Model
{
    /** @use HasFactory<\Database\Factories\ModalityVisualizationFactory> */
    use HasFactory;

    public function context_file()
    {
        return $this->hasOne(Files::class, 'id', 'context_file_id');
    }
    public function image_file()
    {
        return $this->hasOne(Files::class, 'id', 'image_file_id');
    }
}
