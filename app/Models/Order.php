<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['nouvelle', 'confirmee', 'expediee', 'livree', 'annulee'];

    protected $fillable = [
        'order_number', 'customer_name', 'phone', 'city', 'address',
        'note', 'total', 'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmee' => 'Confirmée',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => 'Nouvelle',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'confirmee' => '#0a9396',
            'expediee' => '#7b68ee',
            'livree' => '#2a9d8f',
            'annulee' => '#e76f51',
            default => '#1d9bf0',
        };
    }
}
