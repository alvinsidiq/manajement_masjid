<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class UserBookingDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::query()
            ->where('user_id', $user->user_id)
            ->with(['ruangan', 'pemesanan'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('user.booking.dashboard', compact('bookings'));
    }
}
