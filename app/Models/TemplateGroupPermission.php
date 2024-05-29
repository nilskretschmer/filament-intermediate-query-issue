<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TemplateGroupPermission extends Pivot
{
    use HasUuids;

    protected $table = 'template_group_permission';
}
