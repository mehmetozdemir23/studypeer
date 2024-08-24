<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['group_id', 'title', 'description', 'scheduled_at'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}