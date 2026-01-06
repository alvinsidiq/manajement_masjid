<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApprovePemesananRequest;
use App\Http\Requests\Admin\RejectPemesananRequest;
use App\Http\Requests\Admin\FilterPemesananRequest;
use App\Http\Requests\Admin\StorePemesananRequest;
use App\Http\Requests\Admin\UpdatePemesananRequest;
use App\Http\Requests\Admin\CompletePemesananRequest;
use App\Models\Pemesanan;
use App\Services\PemesananService;
use App\Repositories\PemesananRepository;
use Illuminate\Http\Request;
use App\Notifications\PemesananApproved;
use App\Notifications\PemesananRejected;
use App\Notifications\PemesananCancelled;
use App\Http\Requests\Admin\CancelPemesananRequest;
use Illuminate\Support\Facades\Schema;

class PemesananController extends Controller
{
    public function __construct(private PemesananService $service, private PemesananRepository $repo)
    {
        // Autentikasi & verifikasi dasar
        $this->middleware(['auth','verified','active']);
        // Akses admin untuk seluruh resource kecuali approve/reject
        $this->middleware(['role:admin'])->except(['approve','reject']);
        // Akses takmir khusus untuk approve/reject (sesuai requirement)
        $this->middleware(['role:takmir'])->only(['approve','reject']);
        $this->authorizeResource(Pemesanan::class, 'pemesanan');
    }

    public function index(FilterPemesananRequest $request)
    {
        if (! Schema::hasTable('pemesanan')) {
            return view('admin.pemesanan.setup');
        }

        $params = $request->validatedQuery();
        $items  = $this->repo->search($params);
        return view('admin.pemesanan.index', array_merge($params, ['items'=>$items]));
    }

    public function create()
    {
        $users = \App\Models\User::orderBy('username')->get(['user_id','username']);
        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        $bookings = \App\Models\Booking::orderBy('created_at','desc')->get(['booking_id','ruangan_id','user_id','hari_tanggal','jam','status']);
        $jadwals = \App\Models\Jadwal::orderBy('tanggal_mulai','desc')->get(['jadwal_id','kegiatan_id','ruangan_id','tanggal_mulai','tanggal_selesai']);
        return view('admin.pemesanan.create', compact('users','ruangans','bookings','jadwals'));
    }

    public function store(StorePemesananRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.pemesanan.index')->with('status','Pemesanan dibuat.');
    }

    public function show(Pemesanan $pemesanan)
    {
        $p = $pemesanan->load(['user','ruangan','booking','jadwal','payment']);
        return view('admin.pemesanan.show', compact('p'));
    }

    public function edit(Pemesanan $pemesanan)
    {
        $users = \App\Models\User::orderBy('username')->get(['user_id','username']);
        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        $bookings = \App\Models\Booking::orderBy('created_at','desc')->get(['booking_id','ruangan_id','user_id','hari_tanggal','jam','status']);
        $jadwals = \App\Models\Jadwal::orderBy('tanggal_mulai','desc')->get(['jadwal_id','kegiatan_id','ruangan_id','tanggal_mulai','tanggal_selesai']);
        return view('admin.pemesanan.edit', compact('pemesanan','users','ruangans','bookings','jadwals'));
    }
    public function update(UpdatePemesananRequest $request, Pemesanan $pemesanan)
    {
        $pemesanan->update($request->validated());
        return redirect()->route('admin.pemesanan.index')->with('status','Pemesanan diperbarui.');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return redirect()->route('admin.pemesanan.index')->with('status','Pemesanan dihapus.');
    }

    public function approve(ApprovePemesananRequest $request, Pemesanan $pemesanan)
    {
        $this->authorize('approve', $pemesanan);
        $this->service->approve($pemesanan, $request->input('catatan'));
        return back()->with('status','Pemesanan disetujui.');
    }

    public function reject(RejectPemesananRequest $request, Pemesanan $pemesanan)
    {
        $this->authorize('reject', $pemesanan);
        $this->service->reject($pemesanan, $request->input('alasan_penolakan'), $request->input('catatan'));
        return back()->with('status','Pemesanan ditolak.');
    }

    public function cancel(CancelPemesananRequest $request, Pemesanan $pemesanan)
    {
        $this->authorize('cancel', $pemesanan);

        $this->service->cancel(
            p: $pemesanan,
            alasan: (string) $request->string('alasan_pembatalan'),
            catatan: $request->input('catatan'),
            byUserId: auth()->id(),
            ip: $request->ip(),
            ua: $request->userAgent()
        );

        return back()->with('status','Pemesanan dibatalkan.');
    }

    public function complete(CompletePemesananRequest $request, Pemesanan $pemesanan)
    {
        $this->authorize('complete', $pemesanan);
        $this->service->complete($pemesanan);
        return back()->with('status','Pemesanan selesai.');
    }
}
