<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnarchiveKegiatanRequest;
use App\Models\Kegiatan;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ArsipKegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:admin']);
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $jenis = $request->input('jenis_kegiatan');
        $from = $request->input('date_from');
        $to   = $request->input('date_to');
        $sort = $request->input('sort','archived_at');
        $dir  = $request->input('dir','desc');

        $items = Kegiatan::query()->archived()
            ->when($q, function($qq) use ($q){
                $qq->where(function($w) use ($q){
                    $w->where('nama_kegiatan','like',"%$q%")
                      ->orWhere('deskripsi','like',"%$q%")
                      ->orWhere('penanggung_jawab','like',"%$q%");
                });
            })
            ->when($jenis, fn($qq)=>$qq->where('jenis_kegiatan',$jenis))
            ->when($from, fn($qq)=>$qq->where('archived_at','>=',\Carbon\Carbon::parse($from,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($to, fn($qq)=>$qq->where('archived_at','<=',\Carbon\Carbon::parse($to,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort,$dir)
            ->paginate(12)
            ->withQueryString();

        return view('admin.arsip-kegiatan.index', compact('items','q','jenis','from','to','sort','dir'));
    }

    public function show(Kegiatan $kegiatan)
    {
        abort_unless($kegiatan->is_archived, 404);
        return view('admin.arsip-kegiatan.show', compact('kegiatan'));
    }

    public function unarchive(UnarchiveKegiatanRequest $request, Kegiatan $kegiatan)
    {
        $this->authorize('unarchive', $kegiatan);

        $kegiatan->is_archived = false;
        $kegiatan->archived_at = null;
        $kegiatan->archived_by = null;
        $kegiatan->archive_reason = null;
        $kegiatan->save();

        try {
            if (class_exists(AuditLog::class)) {
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action'  => 'kegiatan.unarchive',
                    'ip' => $request->ip(),
                    'user_agent' => substr((string)$request->userAgent(), 0, 255),
                    'context' => [
                        'entity_type' => Kegiatan::class,
                        'entity_id'   => $kegiatan->getKey(),
                        'changes' => ['after' => ['is_archived' => false]],
                    ],
                ]);
            }
        } catch (\Throwable $e) {}

        return redirect()->route('admin.kegiatan.show',$kegiatan)->with('status','Kegiatan dibuka kembali.');
    }
}

