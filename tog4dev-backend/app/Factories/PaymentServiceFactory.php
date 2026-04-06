<?php 

namespace App\Factories;

use App\Services\PaymentGatewayInterface;
use App\Services\NetworkPaymentService;
use App\Services\PaytabsPaymentService;
use InvalidArgumentException;

class PaymentServiceFactory
{
    public static function create(string $paymentMethod): PaymentGatewayInterface
    {
        switch ($paymentMethod) {
            case 'Network':
                return new NetworkPaymentService();
            case 'Paytabs':
                return new PaytabsPaymentService();
            default:
                throw new InvalidArgumentException('Unsupported payment method');
        }
    }
}
