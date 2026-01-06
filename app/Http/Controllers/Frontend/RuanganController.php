<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RuanganFilterRequest;
use App\Models\{Ruangan, Pemesanan};
use Illuminate\Support\Carbon;

class RuanganController extends Controller
{
    public function index(RuanganFilterRequest $request)
    {
        // gunakan input() agar nilai kosong menjadi null (bukan Stringable truthy)
        $q  = $request->input('q');
        $st = $request->input('status');
        $fa = $request->input('fasilitas');
        $sort = $request->input('sort', 'nama_ruangan');
        $dir = $request->input('dir', 'asc');

        $items = Ruangan::query()
            ->when($st, fn($qq)=>$qq->where('status',$st))
            ->when($q, fn($qq)=>$qq->where(function($w) use($q){
                $w->where('nama_ruangan','like',"%$q%")
                  ->orWhere('deskripsi','like',"%$q%");
            }))
            ->when($fa, fn($qq)=>$qq->where('fasilitas','like',"%$fa%"))
            ->orderBy($sort, $dir)
            ->paginate(9)
            ->withQueryString();

        $fasilitasList = ['AC','Sound System','Proyektor','Kipas','Karpet','Mimbar'];

        return view('public.ruangan.index', compact('items','q','st','fa','sort','dir','fasilitasList'));
    }

    public function show(string $id)
    {
        $r = Ruangan::findOrFail($id);

        $start = Carbon::now('Asia/Jakarta')->startOfDay();
        $end   = Carbon::now('Asia/Jakarta')->addDays(14)->endOfDay();

        $pemesanan = Pemesanan::with(['booking','jadwal'])
            ->where('ruangan_id', $r->ruangan_id)
            ->whereIn('status', ['diterima'])
            ->where(function($q) use ($start, $end){
                $q->whereHas('booking', function($b) use ($start,$end){
                    $b->whereBetween('hari_tanggal', [ $start->clone()->utc(), $end->clone()->utc() ]);
                })->orWhereHas('jadwal', function($j) use ($start,$end){
                    $j->whereBetween('tanggal_mulai', [ $start->clone()->utc(), $end->clone()->utc() ]);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return view('public.ruangan.show', [
            'r' => $r,
            'blocked' => $pemesanan,
            'rangeStart' => $start,
            'rangeEnd' => $end,
        ]);
    }
}
