<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalRequest;
use App\Http\Requests\Admin\UpdateJadwalRequest;
use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Ruangan;
use App\Services\JadwalService;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function __construct(private JadwalService $service)
    {
        $this->middleware(['auth','verified','active','role:admin']);
        $this->authorizeResource(Jadwal::class, 'jadwal');
    }

    public function index(Request $request)
    {
        $q      = $request->input('q');
        $rid    = $request->input('ruangan_id');
        $kid    = $request->input('kegiatan_id');
        $status = $request->input('status');
        $df     = $request->input('date_from');
        $dt     = $request->input('date_to');
        $sort   = $request->input('sort','tanggal_mulai');
        $dir    = $request->input('dir','asc');

        $items = Jadwal::query()->withJoins()
            ->when($q, function($qq) use($q){
                $qq->where(function($w) use ($q){
                    $w->whereHas('kegiatan', fn($k)=>$k->where('nama_kegiatan','like',"%$q%"))
                      ->orWhereHas('ruangan', fn($r)=>$r->where('nama_ruangan','like',"%$q%"))
                      ->orWhere('catatan','like',"%$q%");
                });
            })
            ->when($rid, fn($qq)=>$qq->where('ruangan_id',$rid))
            ->when($kid, fn($qq)=>$qq->where('kegiatan_id',$kid))
            ->when($status, fn($qq)=>$qq->where('status',$status))
            ->dateRange($df,$dt)
            ->orderBy($sort,$dir)
            ->paginate(12)
            ->withQueryString();

        $kegiatans = Kegiatan::active()->orderBy('nama_kegiatan')->get(['kegiatan_id','nama_kegiatan']);
        $ruangans  = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);

        return view('admin.jadwal.index', compact('items','kegiatans','ruangans','q','rid','kid','status','df','dt','sort','dir'));
    }

    public function create()
    {
        $kegiatans = Kegiatan::active()->orderBy('nama_kegiatan')->get(['kegiatan_id','nama_kegiatan']);
        $ruangans  = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        return view('admin.jadwal.create', compact('kegiatans','ruangans'));
    }

    public function store(StoreJadwalRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.jadwal.index')->with('status','Jadwal dibuat.');
    }

    public function show(Jadwal $jadwal)
    {
        return view('admin.jadwal.show', ['j'=>$jadwal->load(['kegiatan','ruangan'])]);
    }

    public function edit(Jadwal $jadwal)
    {
        $kegiatans = Kegiatan::active()->orderBy('nama_kegiatan')->get(['kegiatan_id','nama_kegiatan']);
        $ruangans  = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        return view('admin.jadwal.edit', compact('jadwal','kegiatans','ruangans'));
    }

    public function update(UpdateJadwalRequest $request, Jadwal $jadwal)
    {
        $this->service->update($jadwal, $request->validated());
        return redirect()->route('admin.jadwal.index')->with('status','Jadwal diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('status','Jadwal dihapus.');
    }
}

