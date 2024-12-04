<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalityReadingWriting extends Model
{
    /** @use HasFactory<\Database\Factories\ModalityReadingWritingFactory> */
    use HasFactory;

    public function context_file()
    {
        return $this->hasOne(Files::class, 'id', 'context_file_id');
    }
}
