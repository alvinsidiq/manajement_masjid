<?php

namespace App\Enums;

enum StatusBooking: string
{
    case HOLD      = 'hold';
    case EXPIRED   = 'expired';
    case SUBMITTED = 'submitted';
    case CANCELLED = 'cancelled';
}