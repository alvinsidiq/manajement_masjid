<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKegiatanRequest;
use App\Http\Requests\Admin\UpdateKegiatanRequest;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GenericInfo;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Admin\ArchiveKegiatanRequest;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:admin']);
        $this->authorizeResource(Kegiatan::class, 'kegiatan');
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $jenis = $request->input('jenis_kegiatan');
        $sort = $request->input('sort','created_at');
        $dir  = $request->input('dir','desc');

        $kegiatan = Kegiatan::query()
            ->when($q, function($qq) use ($q){
                $qq->where(function($w) use ($q){
                    $w->where('nama_kegiatan','like',"%$q%")
                      ->orWhere('deskripsi','like',"%$q%")
                      ->orWhere('penanggung_jawab','like',"%$q%");
                });
            })
            ->when($jenis, fn($qq) => $qq->where('jenis_kegiatan',$jenis))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.kegiatan.index', compact('kegiatan','q','jenis','sort','dir'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(StoreKegiatanRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->storeFoto($request->file('foto'), $data['nama_kegiatan']);
        }
        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $this->storeDokumen($request->file('dokumen'), $data['nama_kegiatan']);
        }

        $kegiatan = Kegiatan::create($data);

        try {
            $takmirRole = Role::where('name','takmir')->first();
            if ($takmirRole) {
                $takmirs = $takmirRole->users()->get();
                if ($takmirs->count() > 0) {
                    Notification::send($takmirs, new GenericInfo(
                        subject: 'Kegiatan baru dibuat',
                        content: 'Kegiatan "'.$kegiatan->nama_kegiatan.'" ('.$kegiatan->jenis_kegiatan->value.") telah dibuat.",
                        meta: ['jenis'=>'kegiatan']
                    ));
                }
            }
        } catch (\Throwable $e) {
            // Abaikan error notifikasi
        }

        return redirect()->route('admin.kegiatan.index')->with('status','Kegiatan dibuat.');
    }

    public function show(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(UpdateKegiatanRequest $request, Kegiatan $kegiatan)
    {
        $data = $request->validated();
        if ($request->hasFile('foto')) {
            if ($kegiatan->foto && Storage::disk('public')->exists($kegiatan->foto)) {
                Storage::disk('public')->delete($kegiatan->foto);
            }
            $data['foto'] = $this->storeFoto($request->file('foto'), $data['nama_kegiatan'] ?? $kegiatan->nama_kegiatan);
        }
        if ($request->hasFile('dokumen')) {
            if ($kegiatan->dokumen && Storage::disk('public')->exists($kegiatan->dokumen)) {
                Storage::disk('public')->delete($kegiatan->dokumen);
            }
            $data['dokumen'] = $this->storeDokumen($request->file('dokumen'), $data['nama_kegiatan'] ?? $kegiatan->nama_kegiatan);
        }
        $kegiatan->update($data);
        return redirect()->route('admin.kegiatan.index')->with('status','Kegiatan diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('admin.kegiatan.index')->with('status','Kegiatan dihapus.');
    }

    public function archive(ArchiveKegiatanRequest $request, Kegiatan $kegiatan)
    {
        $this->authorize('archive', $kegiatan);

        $kegiatan->is_archived = true;
        $kegiatan->archived_at = now();
        $kegiatan->archived_by = auth()->id();
        $kegiatan->archive_reason = (string) $request->string('archive_reason');
        $kegiatan->save();

        try {
            if (class_exists(AuditLog::class)) {
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action'  => 'kegiatan.archive',
                    'ip' => request()->ip(),
                    'user_agent' => substr((string)request()->userAgent(), 0, 255),
                    'context' => [
                        'entity_type' => Kegiatan::class,
                        'entity_id'   => $kegiatan->getKey(),
                        'changes' => ['after' => ['is_archived' => true]],
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // ignore audit log failure
        }

        return redirect()
            ->route('admin.kegiatan.show',$kegiatan)
            ->with('status','Kegiatan diarsipkan.');
    }

    private function storeFoto($file, string $nama): string
    {
        $slug = Str::slug($nama);
        $ext  = $file->getClientOriginalExtension();
        $name = $slug.'-'.uniqid().'.'.$ext;
        return $file->storeAs('kegiatan', $name, 'public');
    }

    private function storeDokumen($file, string $nama): string
    {
        $slug = Str::slug($nama);
        $ext  = $file->getClientOriginalExtension();
        $name = $slug.'-lampiran-'.uniqid().'.'.$ext;
        return $file->storeAs('kegiatan/dokumen', $name, 'public');
    }
}
