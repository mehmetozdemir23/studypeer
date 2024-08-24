<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public const string ADMIN = 'admin';

    public const string TUTOR = 'tutor';

    public const string STUDENT = 'student';

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
