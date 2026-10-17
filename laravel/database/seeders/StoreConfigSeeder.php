<?php

namespace Database\Seeders;

use App\Models\StoreConfig;
use Illuminate\Database\Seeder;

class StoreConfigSeeder extends Seeder
{
    /**
     * Configuración inicial de la tienda.
     */
    public function run(): void
    {
        StoreConfig::set('announcement', '🔥 Temporada de ofertas: hasta 30% de descuento en productos seleccionados');
        StoreConfig::set('whatsapp', (string) config('store.whatsapp'));
        StoreConfig::set('free_shipping_over', (string) config('store.free_shipping_over'));
    }
}
