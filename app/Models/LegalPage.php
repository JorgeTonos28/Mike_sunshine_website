<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = ['slug', 'title', 'content_blocks', 'is_approved', 'version'];

    protected function casts(): array
    {
        return ['content_blocks' => 'array', 'is_approved' => 'boolean'];
    }
}
