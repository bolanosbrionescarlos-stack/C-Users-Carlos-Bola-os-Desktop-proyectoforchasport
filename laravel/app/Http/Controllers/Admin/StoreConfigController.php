<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreConfigController extends Controller
{
    private const KEYS = [
        'whatsapp',
        'announcement',
        'free_shipping_over',
    ];

    public function edit(): View
    {
        $config = collect(self::KEYS)
            ->mapWithKeys(fn (string $key) => [
                $key => StoreConfig::get($key, $this->defaultFor($key)),
            ]);

        return view('admin.config.edit', ['config' => $config]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'whatsapp' => ['required', 'string', 'max:20'],
            'announcement' => ['nullable', 'string', 'max:255'],
            'free_shipping_over' => ['required', 'numeric', 'min:0'],
        ]);

        foreach (self::KEYS as $key) {
            StoreConfig::set($key, isset($data[$key]) ? (string) $data[$key] : null);
        }

        return back()->with('status', 'Configuración guardada.');
    }

    private function defaultFor(string $key): string
    {
        return match ($key) {
            'whatsapp' => (string) config('store.whatsapp'),
            'free_shipping_over' => (string) config('store.free_shipping_over'),
            default => '',
        };
    }
}
