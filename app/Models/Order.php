<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['reference', 'submission_hash', 'status', 'customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'message', 'delivery_method', 'subtotal_minor', 'currency', 'privacy_accepted_at', 'terms_version', 'admin_notes', 'notification_status', 'notified_at'];

    protected $hidden = ['submission_hash'];

    protected function casts(): array
    {
        return [
            'customer_name' => 'encrypted', 'customer_email' => 'encrypted', 'customer_phone' => 'encrypted',
            'shipping_address' => 'encrypted', 'message' => 'encrypted', 'admin_notes' => 'encrypted',
            'privacy_accepted_at' => 'datetime', 'notified_at' => 'datetime', 'subtotal_minor' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
