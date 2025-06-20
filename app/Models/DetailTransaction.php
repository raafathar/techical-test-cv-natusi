<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaction extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction ::class);
    }
}
