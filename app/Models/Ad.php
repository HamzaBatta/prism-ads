<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    protected $fillable = ['user_id', 'text', 'remaining_users'];

    public function media(): HasMany {
        return $this->hasMany(Media::class);
    }
}
