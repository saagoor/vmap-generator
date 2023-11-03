<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vmap extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function adBreaks()
    {
        return $this->hasMany(VmapAdBreak::class);
    }
}
