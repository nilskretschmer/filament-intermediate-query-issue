<?php

namespace App\Models;

use App\Models\PermissionUser;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'array'
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->using(GroupUser::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->using(PermissionUser::class);
    }

    public function getFilamentName(): string
    {
        return "{$this->name} ({$this->email})";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }
}
