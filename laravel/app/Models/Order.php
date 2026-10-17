<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'department',
        'province',
        'district',
        'address',
        'reference',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'order_status',
        'subtotal',
        'discount',
        'total',
        'coupon_code',
        'payment_ref',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'payment_status' => PaymentStatus::class,
            'order_status' => OrderStatus::class,
        ];
    }

    /**
     * El público identifica el pedido por su código (FS-YYMMDD-XXXXX).
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return $this->order_status->label();
    }

    public function paymentStatusLabel(): string
    {
        return $this->payment_status->label();
    }

    public function fullAddress(): string
    {
        return collect([$this->address, $this->district, $this->province, $this->department])
            ->filter()
            ->implode(', ');
    }
}
