<?php

namespace App\Enums;

enum StatusBooking: string
{
    case HOLD      = 'hold';
    case PROSES    = 'proses';
    case SETUJU    = 'setuju';
    case TOLAK     = 'tolak';
    case EXPIRED   = 'expired';
    case CANCELLED = 'cancelled';
}
