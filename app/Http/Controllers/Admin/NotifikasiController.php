<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{StoreNotifikasiRequest, UpdateNotifikasiRequest};
use App\Models\{Notifikasi, User};
use App\Enums\JenisReferensi;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function __construct(private NotifikasiService $svc)
    {
        $this->middleware(['auth','verified','active','role:admin|takmir|bendahara']);
        $this->authorizeResource(Notifikasi::class, 'notifikasi');
    }

    public function index(Request $request)
    {
        $q=$request->q; $st=$request->status; $jr=$request->jenis; $uid=$request->user_id; $df=$request->date_from; $dt=$request->date_to; $sort=$request->input('sort','created_at'); $dir=$request->input('dir','desc');
        $items = Notifikasi::query()->with('user')
            ->when($q, fn($qq)=>$qq->where('pesan','like',"%$q%"))
            ->when($st, fn($qq)=>$qq->where('status_pengiriman','like',"%$st%"))
            ->when($jr, fn($qq)=>$qq->where('jenis_referensi',$jr))
            ->when($uid, fn($qq)=>$qq->where('user_id',$uid))
            ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort,$dir)->paginate(15)->withQueryString();
        $users = User::orderBy('username')->get(['user_id','username']);
        return view('admin.notifikasi.index', compact('items','q','st','jr','uid','df','dt','sort','dir','users'));
    }

    public function create()
    { $users = User::orderBy('username')->get(['user_id','username']); return view('admin.notifikasi.create', compact('users')); }

    public function store(StoreNotifikasiRequest $request)
    {
        $data = $request->validated();
        $jenis = JenisReferensi::from($data['jenis_referensi']);
        $this->svc->sendGeneric((int)$data['user_id'], $data['pesan'], $jenis, $data['referensi_id'] ?? null);
        return redirect()->route('admin.notifikasi.index')->with('status','Notifikasi dijadwalkan (queued).');
    }

    public function show(Notifikasi $notifikasi)
    { return view('admin.notifikasi.show', ['n'=>$notifikasi->load('user')]); }

    public function edit(Notifikasi $notifikasi)
    { return view('admin.notifikasi.edit', compact('notifikasi')); }

    public function update(UpdateNotifikasiRequest $request, Notifikasi $notifikasi)
    { $notifikasi->update($request->validated()); return back()->with('status','Notifikasi diperbarui.'); }

    public function destroy(Notifikasi $notifikasi)
    { $notifikasi->delete(); return back()->with('status','Notifikasi dihapus.'); }

    public function resend(Notifikasi $notifikasi)
    {
        $this->authorize('resend',$notifikasi);
        $this->svc->sendGeneric($notifikasi->user_id, $notifikasi->pesan, $notifikasi->jenis_referensi, $notifikasi->referensi_id);
        return back()->with('status','Notifikasi dikirim ulang.');
    }
}

