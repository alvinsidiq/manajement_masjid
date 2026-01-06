<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:bendahara|admin']);
    }

    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $items = Booking::query()
            ->with(['user','ruangan','pemesanan.payment'])
            ->when($q, function($qq) use ($q){
                $qq->whereHas('user', fn($w)=>$w->where('username','like',"%$q%"))
                   ->orWhereHas('ruangan', fn($w)=>$w->where('nama_ruangan','like',"%$q%"));
            })
            ->orderBy('created_at','desc')
            ->paginate(12)
            ->withQueryString();

        return view('bendahara.booking.index', compact('items','q'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user','ruangan','pemesanan.payment']);
        return view('bendahara.booking.show', compact('booking'));
    }
}

