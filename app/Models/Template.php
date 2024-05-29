<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Template extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)
            ->using(GroupTemplate::class);
    }

    public function groupPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)
            ->using(TemplateGroupPermission::class);
    }

    public function allowedTargets(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class)
            ->using(DestinationTemplate::class);
    }
}
