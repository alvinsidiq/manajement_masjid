<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StatusFilterRequest;
use App\Models\{Booking, Pemesanan, Ruangan};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active']);
    }

    /**
     * GET /user/status — Timeline gabungan.
     */
    public function index(StatusFilterRequest $request)
    {
        $user = $request->user();
        $type = $request->input('type','all');
        $status = $request->input('status');
        $q = $request->input('q');
        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $tz = 'Asia/Jakarta';

        // Query Booking milik user
        $qBooking = Booking::query()->with('ruangan')
            ->where('user_id', $user->user_id)
            ->when($status && $type!=='pemesanan', fn($qq)=>$qq->where('status',$status))
            ->when($q, function($qq) use ($q){
                $qq->whereHas('ruangan', fn($r)=>$r->where('nama_ruangan','like',"%$q%"));
            })
            ->when($from, fn($qq)=>$qq->where('created_at','>=', Carbon::parse($from,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($to, fn($qq)=>$qq->where('created_at','<=', Carbon::parse($to,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderByDesc('created_at');

        // Query Pemesanan milik user
        $qPemesanan = Pemesanan::query()->with(['ruangan','booking'])
            ->where('user_id', $user->user_id)
            ->when($status && $type!=='booking', fn($qq)=>$qq->where('status',$status))
            ->when($q, function($qq) use ($q){
                $qq->where('tujuan_pemesanan','like',"%$q%")
                   ->orWhereHas('ruangan', fn($r)=>$r->where('nama_ruangan','like',"%$q%"));
            })
            ->when($from, fn($qq)=>$qq->where('created_at','>=', Carbon::parse($from,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($to, fn($qq)=>$qq->where('created_at','<=', Carbon::parse($to,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderByDesc('created_at');

        $items = collect();
        if ($type==='all' || $type==='booking') {
            foreach ($qBooking->limit(200)->get() as $b) {
                $items->push([
                    'kind' => 'booking',
                    'id' => $b->booking_id,
                    'title' => 'Booking #'.$b->booking_id.' — '.($b->ruangan->nama_ruangan ?? '-'),
                    'status' => ucfirst($b->status->value ?? (string)$b->status),
                    'when' => $b->hari_tanggal?->timezone($tz)->format('d M Y').' '.$b->jam,
                    'created_at' => $b->created_at,
                    'link' => route('user.booking.show', $b),
                    'note' => null,
                ]);
            }
        }
        if ($type==='all' || $type==='pemesanan') {
            foreach ($qPemesanan->limit(200)->get() as $p) {
                $items->push([
                    'kind' => 'pemesanan',
                    'id' => $p->pemesanan_id,
                    'title' => 'Pemesanan #'.$p->pemesanan_id.' — '.($p->ruangan->nama_ruangan ?? '-'),
                    'status' => Str::headline($p->status->value),
                    'when' => $p->booking?->hari_tanggal?->timezone($tz)->format('d M Y').' '.($p->booking?->jam ?? ''),
                    'created_at' => $p->created_at,
                    'link' => route('user.pemesanan.show', $p),
                    'note' => $p->tujuan_pemesanan,
                ]);
            }
        }

        // Urut & paginate manual
        $items = $items->sortByDesc('created_at')->values();
        $page = (int)($request->input('page',1));
        $perPage = 12;
        $slice = $items->slice(($page-1)*$perPage, $perPage)->values();
        $paginator = new LengthAwarePaginator($slice, $items->count(), $perPage, $page, [
            'path' => url()->current(), 'query' => $request->query()
        ]);

        return view('user.status.index', [
            'items' => $paginator,
            'type' => $type,
            'statusFilter' => $status,
            'q' => $q,
            'date_from' => $from,
            'date_to' => $to,
        ]);
    }

    /**
     * GET /user/status/{key} — key: "booking-123" atau "pemesanan-456"
     */
    public function show(string $key)
    {
        [$kind,$id] = array_pad(explode('-', $key, 2), 2, null);
        abort_unless(in_array($kind,['booking','pemesanan']) && ctype_digit((string)$id), 404);

        if ($kind==='booking') {
            $b = Booking::with('ruangan','pemesanan')->findOrFail($id);
            $this->authorize('view', $b);
            return view('user.status.show', ['kind'=>'booking','booking'=>$b]);
        }
        $p = Pemesanan::with(['ruangan','booking'])->findOrFail($id);
        $this->authorize('view', $p); // gunakan policy Pemesanan (sesi 12)
        return view('user.status.show', ['kind'=>'pemesanan','pemesanan'=>$p]);
    }

    // Resource methods lain (tidak digunakan di modul ini)
    public function create(){ abort(404); }
    public function store(){ abort(404); }
    public function edit(){ abort(404); }
    public function update(){ abort(404); }
    public function destroy(){ abort(404); }
}