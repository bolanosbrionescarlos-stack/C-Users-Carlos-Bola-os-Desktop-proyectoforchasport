<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_name' => trim((string) $this->input('customer_name')),
            'customer_phone' => trim((string) $this->input('customer_phone')),
            'customer_email' => $this->nullableString('customer_email'),
            'province' => $this->nullableString('province'),
            'district' => $this->nullableString('district'),
            'address' => $this->nullableString('address'),
            'reference' => $this->nullableString('reference'),
            'notes' => $this->nullableString('notes'),
            'payment_ref' => $this->nullableString('payment_ref'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:190'],
            'department' => ['required', 'string', Rule::in(config('store.departments'))],
            'province' => ['required', 'string', 'max:120'],
            'district' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'shipping_method' => ['required', 'string', Rule::in(array_keys(config('store.shipping_methods')))],
            'payment_method' => ['required', 'string', Rule::in(array_keys(config('store.payment_methods')))],
            'payment_ref' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'nombre completo',
            'customer_phone' => 'celular',
            'customer_email' => 'correo electrónico',
            'department' => 'departamento',
            'province' => 'provincia',
            'district' => 'distrito',
            'address' => 'dirección',
            'reference' => 'referencia',
            'shipping_method' => 'método de envío',
            'payment_method' => 'método de pago',
            'payment_ref' => 'referencia de pago',
            'notes' => 'notas',
        ];
    }

    private function nullableString(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }
}
