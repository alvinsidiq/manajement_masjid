<?php

namespace App\Enums;

enum StatusPemesanan: string
{
    case MENUNGGU = 'menunggu_verifikasi';
    case DITERIMA = 'diterima';
    case DITOLAK = 'ditolak';
    case DIBATALKAN = 'dibatalkan';
    case SELESAI = 'selesai';
}

