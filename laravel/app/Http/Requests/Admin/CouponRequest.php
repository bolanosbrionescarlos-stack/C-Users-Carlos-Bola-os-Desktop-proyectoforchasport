<?php

namespace App\Http\Requests\Admin;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $couponId = $this->route('coupon')?->getKey();

        return [
            'code' => ['required', 'string', 'max:40', Rule::unique('coupons', 'code')->ignore($couponId)],
            'type' => ['required', 'string', Rule::enum(CouponType::class)],
            'value' => ['required', 'numeric', 'min:0', function (string $attribute, mixed $value, callable $fail): void {
                if ($this->input('type') === CouponType::Percent->value && (float) $value > 100) {
                    $fail('El porcentaje de descuento no puede superar 100.');
                }
            }],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'active' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'código',
            'type' => 'tipo',
            'value' => 'valor',
            'min_order' => 'pedido mínimo',
            'max_uses' => 'usos máximos',
            'active' => 'activo',
            'expires_at' => 'fecha de expiración',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedAttributes(): array
    {
        $data = $this->safe([
            'code',
            'type',
            'value',
        ]);

        $data['active'] = $this->boolean('active');
        $data['min_order'] = (float) $this->input('min_order', 0);
        $data['max_uses'] = $this->filled('max_uses') ? (int) $this->input('max_uses') : null;
        $data['expires_at'] = $this->filled('expires_at') ? $this->input('expires_at') : null;

        return $data;
    }
}
