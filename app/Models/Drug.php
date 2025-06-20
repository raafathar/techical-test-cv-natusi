<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drug extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detail_transcations()
    {
        return $this->hasMany(Drug::class, 'drug_id'); // atau 'galleries_id' sesuai nama kolom foreign key-nya
    }
}
