<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case MANUAL = 'manual';
    case MIDTRANS = 'midtrans';
    case XENDIT = 'xendit';
}

