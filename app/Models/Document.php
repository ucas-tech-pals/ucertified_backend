<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['signature'];
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
