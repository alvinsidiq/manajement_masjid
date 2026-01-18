<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePemesananRequest;
use App\Models\Booking;
use App\Models\Pemesanan;
use App\Models\Payment;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Services\PemesananService;
use App\Services\PaymentService;
use App\Enums\StatusBooking;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function __construct(private PemesananService $service, private PaymentService $paymentService)
    {
        $this->middleware(['auth','verified','active']);
    }

    public function create(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $tujuan = $request->input('tujuan');
        $booking = null;

        if ($bookingId) {
            $booking = Booking::where('booking_id', $bookingId)
                ->where('user_id', auth()->id())
                ->with('ruangan')
                ->firstOrFail();
        }

        return view('user.pemesanan.create', compact('booking','tujuan'));
    }

    public function store(StorePemesananRequest $request)
    {
        $booking = Booking::where('booking_id', $request->integer('booking_id'))
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->status->value !== StatusBooking::HOLD->value) {
            return back()->withErrors(['booking_id' => 'Booking sudah tidak dalam status hold.'])->withInput();
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['ruangan_id'] = $booking->ruangan_id;
        $data['booking_id'] = $booking->booking_id;

        $this->service->create($data);

        return redirect()
            ->route('user.booking.show', $booking)
            ->with('status','Pemesanan dikirim. Menunggu verifikasi takmir.');
    }

    public function pay(Request $request, Pemesanan $pemesanan)
    {
        abort_unless($pemesanan->user_id === auth()->id(), 403);
        $pemesanan->loadMissing('ruangan','payment');
        if ($pemesanan->status->value !== 'diterima') {
            return back()->withErrors(['payment' => 'Pembayaran hanya tersedia setelah pemesanan disetujui.']);
        }

        $payment = $pemesanan->payment;
        if ($payment && $payment->status === PaymentStatus::PAID) {
            return back()->with('status','Pembayaran sudah lunas.');
        }

        $gateway = $request->input('gateway');
        $method  = $request->input('method');
        $amount  = (float) ($pemesanan->ruangan->harga ?? 0);
        if ($amount <= 0) {
            $amount = 100000; // fallback minimal bila harga belum diisi
        }

        if ($payment && $payment->status === PaymentStatus::PENDING) {
            // Reuse existing pending Xendit payment
            if ($gateway === 'xendit' && $payment->gateway === PaymentGateway::XENDIT && $payment->snap_url_or_qris) {
                if (($payment->amount ?? 0) <= 0) {
                    $payment->amount = $amount;
                    $payment->save();
                }
                return redirect()->away($payment->snap_url_or_qris);
            }
            // If previous was manual and user chooses Xendit, expire it and create new
            if ($gateway === 'xendit' && $payment->gateway === PaymentGateway::MANUAL) {
                $payment->status = PaymentStatus::EXPIRED;
                $payment->save();
            } elseif ($payment->gateway === PaymentGateway::MANUAL) {
                return back()->with('status','Menunggu konfirmasi pembayaran tunai oleh admin.');
            }
        }

        $newPayment = $this->paymentService->create([
            'pemesanan_id' => $pemesanan->getKey(),
            'gateway' => $gateway === 'xendit' ? PaymentGateway::XENDIT->value : PaymentGateway::MANUAL->value,
            'method' => $gateway === 'xendit' ? ($method ?? 'wallet_bank') : 'cash',
            'amount' => $amount,
            'currency' => 'IDR',
        ]);

        if ($newPayment->gateway === PaymentGateway::XENDIT) {
            return redirect()->away($newPayment->snap_url_or_qris);
        }

        return back()->with('status','Permintaan pembayaran tunai dicatat. Admin akan mengonfirmasi.');
    }

    public function show(Pemesanan $pemesanan)
    {
        abort_unless($pemesanan->user_id === auth()->id(), 403);
        $pemesanan->loadMissing(['ruangan','booking','payment']);
        return view('user.pemesanan.dashboard-show', compact('pemesanan'));
    }
}
