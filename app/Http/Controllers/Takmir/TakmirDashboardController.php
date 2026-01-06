<?php

namespace App\Http\Controllers\Takmir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class TakmirDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $bookingWaiting   = Pemesanan::whereNotNull('booking_id')->where('status','menunggu_verifikasi')->count();
        $bookingApproved  = Pemesanan::whereNotNull('booking_id')->where('status','diterima')->count();
        $bookingRejected  = Pemesanan::whereNotNull('booking_id')->where('status','ditolak')->count();

        $jadwalWaiting    = Pemesanan::whereNull('booking_id')->where('status','menunggu_verifikasi')->count();
        $jadwalApproved   = Pemesanan::whereNull('booking_id')->where('status','diterima')->count();
        $jadwalRejected   = Pemesanan::whereNull('booking_id')->where('status','ditolak')->count();

        $waitingBookings  = Pemesanan::with(['user','ruangan'])
            ->whereNotNull('booking_id')
            ->where('status','menunggu_verifikasi')
            ->latest()
            ->limit(5)
            ->get();

        $waitingJadwals   = Pemesanan::with(['user','ruangan'])
            ->whereNull('booking_id')
            ->where('status','menunggu_verifikasi')
            ->latest()
            ->limit(5)
            ->get();

        return view('takmir.dashboard', compact(
            'bookingWaiting','bookingApproved','bookingRejected',
            'jadwalWaiting','jadwalApproved','jadwalRejected',
            'waitingBookings','waitingJadwals'
        ));
    }
}
