<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class DestinationTemplate extends Pivot
{
    use HasUuids;
}
