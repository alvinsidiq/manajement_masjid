<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:bendahara|admin']);
    }

    public function index(Request $request)
    {
        $q   = $request->string('q')->toString();
        $st  = $request->string('status')->toString();
        $gw  = $request->string('gateway')->toString();
        $df  = $request->input('date_from');
        $dt  = $request->input('date_to');
        $sort= $request->input('sort','created_at');
        $dir = $request->input('dir','desc');

        $items = Payment::query()->with('pemesanan')
            ->when($q, fn($qq)=>$qq->whereHas('pemesanan', fn($w)=>$w->where('tujuan_pemesanan','like',"%$q%")))
            ->when($st, fn($qq)=>$qq->where('status',$st))
            ->when($gw, fn($qq)=>$qq->where('gateway',$gw))
            ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort,$dir)
            ->paginate(12)
            ->withQueryString();

        // Ringkasan
        $summary = [
            'total'       => (clone $items)->total(),
            'paid_count'  => Payment::query()
                ->when($q, fn($qq)=>$qq->whereHas('pemesanan', fn($w)=>$w->where('tujuan_pemesanan','like',"%$q%")))
                ->when($gw, fn($qq)=>$qq->where('gateway',$gw))
                ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
                ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
                ->where('status','paid')->count(),
            'paid_amount' => Payment::query()
                ->when($q, fn($qq)=>$qq->whereHas('pemesanan', fn($w)=>$w->where('tujuan_pemesanan','like',"%$q%")))
                ->when($gw, fn($qq)=>$qq->where('gateway',$gw))
                ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
                ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
                ->where('status','paid')->sum('amount'),
        ];

        return view('bendahara.laporan.transaksi', compact('items','q','st','gw','df','dt','sort','dir','summary'));
    }
}

