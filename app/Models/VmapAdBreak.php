<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmapAdBreak extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_pre_roll' => 'boolean',
        'is_post_roll' => 'boolean',
        'time_offset' => 'integer',
        'repeat_after' => 'integer',
    ];
}
