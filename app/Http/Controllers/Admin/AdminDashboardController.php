<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Ruangan;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'users'   => User::count(),
            'booking' => Booking::count(),
            'ruangan' => Ruangan::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
