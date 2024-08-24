<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withPivot('role');
    }

    public function createdGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'creator_id');
    }

    public function tutoredGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->wherePivot('role', 'tutor');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function isTutorOf(User $user): bool
    {
        return $this->tutoredGroups()->whereHas('members', function ($q) use ($user) {
            $q->where('id', $user->id);
        })->exists();
    }

    public function sharesGroupWith(User $user): bool
    {
        return $this->groups()->whereHas('members', function ($q) use ($user) {
            $q->where('id', $user->id);
        })->exists();
    }

    public function usersInSameGroups(): Collection
    {
        $groupIds = $this->groups()->pluck('id');

        return User::whereHas('groups', function ($q) use ($groupIds) {
            $q->whereIn('id', $groupIds);
        })->whereNot('id', $this->id)->get();
    }

    public function membersOfTutoredGroups(): Collection
    {
        $tutoredGroupIds = $this->tutoredGroups()->pluck('id');

        return User::whereHas('groups', function ($q) use ($tutoredGroupIds) {
            $q->whereIn('id', $tutoredGroupIds);
        })->get();
    }
}
