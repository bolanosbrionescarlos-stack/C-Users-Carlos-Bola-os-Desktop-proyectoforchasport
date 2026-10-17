<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Cupones de demostración de la tienda.
     */
    public function run(): void
    {
        Coupon::query()->create([
            'code' => 'BIENVENIDO10',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 0,
            'max_uses' => null,
            'used_count' => 0,
            'active' => true,
            'expires_at' => null,
        ]);

        Coupon::query()->create([
            'code' => 'FORCHA15',
            'type' => 'percent',
            'value' => 15,
            'min_order' => 150,
            'max_uses' => 100,
            'used_count' => 0,
            'active' => true,
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::query()->create([
            'code' => 'AHORRA20',
            'type' => 'fixed',
            'value' => 20,
            'min_order' => 200,
            'max_uses' => 50,
            'used_count' => 0,
            'active' => true,
            'expires_at' => now()->addMonth(),
        ]);
    }
}
