<?php

namespace App\Enums;

enum JenisReferensi: string
{
    case PEMESANAN = 'pemesanan';
    case PAYMENT   = 'payment';
    case KEGIATAN  = 'kegiatan';
    case UMUM      = 'umum';
}

