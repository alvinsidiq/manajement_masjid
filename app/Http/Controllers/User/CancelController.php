<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\{CancelBookingRequest, CancelPemesananRequest};
use App\Models\{Booking, Pemesanan, User};
use App\Enums\StatusBooking;
use App\Services\{AuditService, NotifikasiService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CancelController extends Controller
{
    public function __construct(
        private AuditService $audit,
        private NotifikasiService $notif
    ){
        $this->middleware(['auth','verified','active']);
    }

    // ===== BOOKING =====
    public function bookingConfirm(Booking $booking)
    {
        $this->authorize('view', $booking);
        if (!in_array($booking->status->value, ['hold','proses'])) abort(400,'Booking tidak dapat dibatalkan.');
        return view('user.booking.cancel', compact('booking'));
    }

    public function bookingProcess(CancelBookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        if (!in_array($booking->status->value, ['hold','proses'])) abort(400,'Booking tidak dapat dibatalkan.');

        DB::transaction(function() use ($booking,$request){
            $old = $booking->status->value;
            $booking->update([
                'status' => StatusBooking::CANCELLED,
                'cancel_reason' => $request->input('reason'),
                'cancelled_at' => now(),
            ]);
            // Audit
            $this->audit->log('booking.cancel','Booking',$booking->booking_id,[
                'from'=>$old,'to'=>'cancelled','reason'=>$request->input('reason')
            ], auth()->user()->user_id);
        });

        // Notifikasi ke user (konfirmasi) & ke takmir/admin (info)
        $u = auth()->user();
        $this->notif->sendGeneric($u->user_id, 'Booking #'.$booking->booking_id.' dibatalkan oleh Anda.', \App\Enums\JenisReferensi::UMUM, $booking->booking_id);
        foreach (User::role('takmir')->get() as $t) {
            $this->notif->sendGeneric($t->user_id, 'Booking #'.$booking->booking_id.' dibatalkan oleh '.$u->username.'.', \App\Enums\JenisReferensi::UMUM, $booking->booking_id);
        }

        return redirect()->route('user.booking.index')->with('status','Booking dibatalkan.');
    }

    // ===== PEMESANAN =====
    public function pemesananConfirm(Pemesanan $pemesanan)
    {
        $this->authorize('update', $pemesanan); // policy Pemesanan dari sesi 12
        if (in_array($pemesanan->status, ['ditolak','selesai','dibatalkan'])) abort(400,'Pemesanan tidak dapat dibatalkan.');
        return view('user.pemesanan.cancel', compact('pemesanan'));
    }

    public function pemesananProcess(CancelPemesananRequest $request, Pemesanan $pemesanan)
    {
        $this->authorize('update', $pemesanan);
        if (in_array($pemesanan->status, ['ditolak','selesai','dibatalkan'])) abort(400,'Pemesanan tidak dapat dibatalkan.');

        DB::transaction(function() use ($pemesanan,$request){
            $old = $pemesanan->status;
            $pemesanan->update([
                'status' => 'dibatalkan',
                'cancel_reason' => $request->input('reason'),
                'cancelled_at' => now(),
                'catatan' => trim(($pemesanan->catatan ? $pemesanan->catatan."\n" : '').'User cancel: '.$request->input('reason')),
            ]);
            // Jika ada booking terkait, set cancelled juga agar slot lepas
            if ($pemesanan->booking_id && $pemesanan->booking) {
                $pemesanan->booking->update(['status'=>StatusBooking::CANCELLED,'cancel_reason'=>$request->input('reason'),'cancelled_at'=>now()]);
            }
            // Audit
            $this->audit->log('pemesanan.cancel','Pemesanan',$pemesanan->pemesanan_id,[
                'from'=>$old,'to'=>'dibatalkan','reason'=>$request->input('reason')
            ], auth()->user()->user_id);
        });

        // Notifikasi ke user & takmir/admin
        $u = auth()->user();
        $this->notif->sendGeneric($u->user_id, 'Pemesanan #'.$pemesanan->pemesanan_id.' dibatalkan oleh Anda.', \App\Enums\JenisReferensi::PEMESANAN, $pemesanan->pemesanan_id);
        foreach (User::role('takmir')->get() as $t) {
            $this->notif->sendGeneric($t->user_id, 'Pemesanan #'.$pemesanan->pemesanan_id.' dibatalkan oleh '.$u->username.'.', \App\Enums\JenisReferensi::PEMESANAN, $pemesanan->pemesanan_id);
        }

        return redirect()->route('user.pemesanan.index')->with('status','Pemesanan dibatalkan.');
    }
}
