<?php

namespace App\Http\Controllers\Takmir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class VerifikasiBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:takmir|admin|bendahara']);
    }

    public function index(Request $request)
    {
        $q   = $request->string('q')->toString();
        $df  = $request->input('date_from');
        $dt  = $request->input('date_to');
        $rid = $request->input('ruangan_id');
        $sort= $request->input('sort','created_at');
        $dir = $request->input('dir','desc');
        $mode = $request->input('mode','verified');

        $items = Pemesanan::query()->with(['user','ruangan','booking'])
            ->whereNotNull('booking_id')
            ->when($mode === 'waiting', fn($qq) => $qq->where('status','menunggu_verifikasi'))
            ->when($mode === 'verified', fn($qq) => $qq->whereIn('status',['diterima','ditolak']))
            ->when($q, function($qq) use ($q) {
                $qq->where('tujuan_pemesanan','like',"%$q%")
                   ->orWhereHas('user', fn($w)=>$w->where('username','like',"%$q%"))
                   ->orWhereHas('ruangan', fn($w)=>$w->where('nama_ruangan','like',"%$q%"));
            })
            ->when($rid, fn($qq)=>$qq->where('ruangan_id',(int)$rid))
            ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort, $dir)
            ->paginate(12)
            ->withQueryString();

        return view('takmir.verifikasi-booking.index', compact('items','q','df','dt','rid','sort','dir','mode'));
    }

    public function show(Pemesanan $pemesanan)
    {
        $this->authorize('view', $pemesanan);
        $p = $pemesanan->load(['user','ruangan','booking']);
        return view('takmir.verifikasi-booking.show', compact('p'));
    }

    public function approveConfirm(Pemesanan $pemesanan)
    {
        $this->authorize('approve', $pemesanan);
        if ($pemesanan->status->value !== 'menunggu_verifikasi') {
            return redirect()->route('takmir.verifikasi-booking.index')
                ->with('status','Pemesanan tidak dalam status menunggu verifikasi.');
        }
        return view('takmir.verifikasi-booking.approve-confirm', ['pemesanan'=>$pemesanan->load(['user','ruangan'])]);
    }
}

