<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Destination extends Model
{
    use HasFactory, HasUuids;

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class)
            ->using(DestinationTemplate::class);
    }
}
