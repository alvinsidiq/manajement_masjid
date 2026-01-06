<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\{StoreBookingRequest, UpdateBookingRequest};
use App\Models\{Booking, Ruangan};
use App\Enums\StatusBooking;
use App\Services\BookingAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function __construct(private BookingAvailabilityService $avail)
    {
        $this->middleware(['auth','verified','active']);
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index(Request $request)
    {
        // Tampilkan daftar ruangan yang tersedia untuk user (status aktif)
        $q = trim((string) $request->input('q'));
        $items = Ruangan::query()
            ->where('status','aktif')
            ->when($q !== '', fn($qq)=>$qq->where(function($w) use ($q){
                $w->where('nama_ruangan','like',"%$q%")
                 ->orWhere('deskripsi','like',"%$q%")
                 ->orWhereJsonContains('fasilitas', $q);
            }))
            ->orderBy('nama_ruangan')
            ->paginate(9)
            ->withQueryString();
        return view('user.booking.rooms', compact('items','q'));
    }

    public function create()
    {
        $ruangan = Ruangan::where('status','aktif')->orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        return view('user.booking.create', compact('ruangan'));
    }

    public function store(StoreBookingRequest $request)
    {
        $u = $request->user();
        $data = $request->validated();
        $tanggal = Carbon::parse($data['hari_tanggal'], 'Asia/Jakarta');

        // Validasi ketersediaan slot
        if (!$this->avail->isAvailable((int)$data['ruangan_id'], $tanggal, $data['jam'].':00')) {
            return back()->withErrors(['hari_tanggal'=>'Slot tidak tersedia.'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => $u->user_id,
            'ruangan_id' => (int)$data['ruangan_id'],
            'hari_tanggal' => $tanggal->clone()->utc(),
            'jam' => $data['jam'].':00',
            'status' => StatusBooking::HOLD,
            'hold_expires_at' => now()->addMinutes(45),
        ]);

        // Simpan tujuan sementara di session untuk diteruskan ke pemesanan
        session(['booking_tujuan_'.$booking->booking_id => $data['tujuan']]);

        return redirect()->route('user.booking.show', $booking)
            ->with('status','Booking dibuat dan di-hold selama 45 menit. Silakan konfirmasi untuk lanjut ke pemesanan.');
    }

    public function show(Booking $booking)
    {
        return view('user.booking.show', [
            'b' => $booking->load(['ruangan','pemesanan.payment']),
            'tujuan' => session('booking_tujuan_'.$booking->booking_id)
        ]);
    }

    public function edit(Booking $booking) { return redirect()->route('user.booking.show',$booking); }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        if ($request->input('aksi') === 'cancel') {
            $booking->status = StatusBooking::CANCELLED;
            $booking->save();
            return redirect()->route('user.booking.index')->with('status','Booking dibatalkan.');
        }
        return back();
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('user.booking.index')->with('status','Booking dihapus.');
    }
}
