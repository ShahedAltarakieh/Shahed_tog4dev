<?php

namespace App\Services;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function handlePayment(Request $request); // Method that all services should implement
}
