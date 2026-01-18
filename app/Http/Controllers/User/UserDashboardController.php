<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Enums\StatusBooking;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $u = $request->user();

        $bookingBase = Booking::query()->where('user_id', $u->user_id);
        $stats = [
            'total' => (clone $bookingBase)->count(),
            'hold' => (clone $bookingBase)->where('status', StatusBooking::HOLD)->count(),
            'proses' => (clone $bookingBase)->where('status', StatusBooking::PROSES)->count(),
            'setuju' => (clone $bookingBase)->where('status', StatusBooking::SETUJU)->count(),
            'rejected' => (clone $bookingBase)->whereIn('status', [
                StatusBooking::TOLAK,
                StatusBooking::CANCELLED,
                StatusBooking::EXPIRED,
            ])->count(),
        ];

        $bookings = Booking::query()
            ->where('user_id', $u->user_id)
            ->with(['ruangan','pemesanan'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $nextHold = Booking::query()
            ->where('user_id', $u->user_id)
            ->where('status', StatusBooking::HOLD)
            ->whereNotNull('hold_expires_at')
            ->where('hold_expires_at', '>', now())
            ->orderBy('hold_expires_at')
            ->first();

        return view('user.dashboard', compact('stats','bookings','nextHold'));
    }
}
