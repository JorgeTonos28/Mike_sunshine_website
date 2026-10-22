<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'privacy_accepted_at', 'read_at', 'notification_status'];

    protected function casts(): array
    {
        return ['name' => 'encrypted', 'email' => 'encrypted', 'message' => 'encrypted', 'privacy_accepted_at' => 'datetime', 'read_at' => 'datetime'];
    }
}
