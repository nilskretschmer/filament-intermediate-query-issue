<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Permission extends Model
{
    use HasFactory, HasUuids;

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->using(GroupPermission::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'template_group_permission')->using(TemplateGroupPermission::class);
    }
}
