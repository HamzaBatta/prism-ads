<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = ['ad_id', 'type', 'path'];

    public function ad() {
        return $this->belongsTo(Ad::class);
    }
}
