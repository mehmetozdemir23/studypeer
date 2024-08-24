<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'creator_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'tutor');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'member');
    }

    public function hasMember(User $user): bool
    {
        return $this->whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->exists();
    }

    public function isCreatedBy(User $user): bool
    {
        return $this->creator_id === $user->id;
    }

    public function isTutoredBy(User $user): bool
    {
        return $this->tutors()->where('id', $user->id)->exists();
    }
}
