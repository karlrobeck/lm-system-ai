<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ModalityAuditory extends Model
{
    /** @use HasFactory<\Database\Factories\ModalityAuditoryFactory> */
    use HasFactory;

    public function context_file(): HasOne
    {
        return $this->hasOne(Files::class, 'id', 'context_file_id');
    }
    public function audio_file(): HasOne
    {
        return $this->hasOne(Files::class, 'id', 'audio_file_id');
    }
}
