<?php

namespace Database\Factories;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(CouponType::cases());

        return [
            'code' => strtoupper(Str::upper(fake()->unique()->bothify('????####'))),
            'type' => $type,
            'value' => $type === CouponType::Percent ? fake()->numberBetween(5, 30) : fake()->randomFloat(2, 5, 30),
            'min_order' => fake()->randomElement([0, 0, 100, 150]),
            'max_uses' => fake()->boolean(30) ? fake()->numberBetween(10, 100) : null,
            'used_count' => 0,
            'active' => true,
            'expires_at' => fake()->boolean(30) ? fake()->dateTimeBetween('+1 week', '+3 months') : null,
        ];
    }
}
