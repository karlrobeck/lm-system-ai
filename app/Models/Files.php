<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Files extends Model
{
    /** @use HasFactory<\Database\Factories\FilesFactory> */
    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
