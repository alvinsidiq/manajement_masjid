<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Enums\StatusPemesanan;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'users'   => User::count(),
            'ruangan' => Ruangan::count(),
            'kegiatan' => Kegiatan::count(),
            'pemesanan_waiting' => Pemesanan::where('status', StatusPemesanan::MENUNGGU)->count(),
            'report_types' => 3,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
