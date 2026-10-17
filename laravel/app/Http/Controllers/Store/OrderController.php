<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Página de confirmación / seguimiento del pedido por su código.
     */
    public function show(Order $order): View
    {
        return view('store.orders.show', compact('order'));
    }
}
