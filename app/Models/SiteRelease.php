<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteRelease extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'previous_content', 'installed_at'];

    protected function casts(): array
    {
        return ['previous_content' => 'array', 'installed_at' => 'datetime'];
    }
}
