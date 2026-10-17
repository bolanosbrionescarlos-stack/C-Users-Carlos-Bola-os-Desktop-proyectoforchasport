<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StoreConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Lee un valor de configuración de la tienda con caché.
     *
     * Solo se cachea el valor escalar: la caché no deserializa objetos
     * (serializable_classes = false) para prevenir gadget chain attacks.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $value = Cache::rememberForever('store_config.'.$key, function () use ($key) {
            return static::query()->where('key', $key)->value('value');
        });

        return $value ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget('store_config.'.$key);
    }
}
