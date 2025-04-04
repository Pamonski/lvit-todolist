<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * Get user's to-do list.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}