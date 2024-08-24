<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedFile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['group_id', 'uploader_id', 'name', 'description', 'file_path'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
