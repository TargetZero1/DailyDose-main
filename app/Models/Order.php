<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Order extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal
     * 
     * @var array<string>
     */
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone', 'customer_address', 
        'total', 'status', 'notes', 'discount_id', 'discount', 'tax', 
        'payment_status', 'uuid'
    ];

    /**
     * Relasi: Order memiliki banyak item
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi: Order dimiliki oleh satu user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
