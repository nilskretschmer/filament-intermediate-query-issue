<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Group extends Model
{
    use HasFactory, HasUuids;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(GroupUser::class);
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class)
            ->using(GroupTemplate::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->using(GroupPermission::class);
    }

    public function templatePermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'template_group_permission')->using(TemplateGroupPermission::class);
    }
}
