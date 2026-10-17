<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de la tienda Forcha Sport
    |--------------------------------------------------------------------------
    | Datos generales del negocio: envíos, pagos y catálogo.
    */

    'currency' => 'PEN',
    'currency_symbol' => 'S/',
    'page_size' => 16,

    /* Monto mínimo de subtotal para obtener envío gratis. */
    'free_shipping_over' => (float) env('STORE_FREE_SHIPPING_OVER', 200),

    /* Número de WhatsApp del negocio, en formato internacional. */
    'whatsapp' => env('STORE_WHATSAPP', '51999999999'),

    'payment_methods' => [
        'yape' => [
            'label' => 'Yape / Plin',
            'icon' => '📱',
            'description' => 'Realiza el pago y envía el comprobante por WhatsApp.',
        ],
        'transfer' => [
            'label' => 'Transferencia bancaria',
            'icon' => '🏦',
            'description' => 'BCP, Interbank o BBVA. Te compartimos los datos por WhatsApp.',
        ],
        'cod' => [
            'label' => 'Pago contra entrega',
            'icon' => '💵',
            'description' => 'Disponible solo para Lima Metropolitana.',
        ],
    ],

    'shipping_methods' => [
        'lima' => [
            'label' => 'Entrega en Lima Metropolitana',
            'description' => '24 a 48 horas hábiles',
            'price' => 10.00,
        ],
        'national' => [
            'label' => 'Envío nacional (Olva / Shalom)',
            'description' => '3 a 7 días hábiles',
            'price' => 15.00,
        ],
        'express' => [
            'label' => 'Envío express Lima (24h)',
            'description' => 'Entrega al día siguiente',
            'price' => 20.00,
        ],
    ],

    'departments' => [
        'Amazonas', 'Áncash', 'Apurímac', 'Arequipa', 'Ayacucho',
        'Cajamarca', 'Callao', 'Cusco', 'Huancavelica', 'Huánuco',
        'Ica', 'Junín', 'La Libertad', 'Lambayeque', 'Lima',
        'Loreto', 'Madre de Dios', 'Moquegua', 'Pasco', 'Piura',
        'Puno', 'San Martín', 'Tacna', 'Tumbes', 'Ucayali',
    ],
];
