<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Files extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'name',
        'study_notes',
        'is_ready',
        'type',
        'owner_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
