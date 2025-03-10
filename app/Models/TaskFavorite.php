<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFavorite extends BaseModel
{
    use HasFactory;

    /**
     * Obtém a tarefa que foi favoritada.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Obtém o usuário que marcou a tarefa como favorita.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
