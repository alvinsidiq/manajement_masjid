<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Enums\StatusKegiatan;
use App\Http\Requests\Frontend\DaftarKegiatanRequest;
use App\Models\KegiatanPendaftaran;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $jenis = $request->input('jenis');

        $items = Kegiatan::query()
            ->active()
            ->where('approval_status', StatusKegiatan::APPROVED)
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('nama_kegiatan', 'like', "%$q%")
                   ->orWhere('deskripsi', 'like', "%$q%")
                   ->orWhere('penanggung_jawab', 'like', "%$q%");
            })
            ->when($jenis, fn($qq) => $qq->where('jenis_kegiatan', $jenis))
            ->orderBy('nama_kegiatan')
            ->paginate(9)
            ->withQueryString();

        return view('public.kegiatan.index', compact('items','q','jenis'));
    }

    public function show(Kegiatan $kegiatan)
    {
        if ($kegiatan->is_archived || $kegiatan->approval_status !== StatusKegiatan::APPROVED) {
            abort(404);
        }
        $upcoming = $kegiatan->jadwals()
            ->where('tanggal_selesai', '>=', now('UTC'))
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->get();
        $tz = 'Asia/Jakarta';
        $sudahDaftar = auth()->check()
            ? $kegiatan->pendaftarans()->where('user_id', auth()->id())->exists()
            : false;
        return view('public.kegiatan.show', compact('kegiatan','upcoming','tz','sudahDaftar'));
    }

    public function daftar(DaftarKegiatanRequest $request, Kegiatan $kegiatan)
    {
        if ($kegiatan->is_archived || $kegiatan->approval_status !== StatusKegiatan::APPROVED) {
            abort(404);
        }

        $data = $request->validated();
        KegiatanPendaftaran::create([
            'kegiatan_id' => $kegiatan->getKey(),
            'user_id' => auth()->id(),
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telephone' => $data['no_telephone'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return back()->with('status','Pendaftaran berhasil dikirim. Kami akan menghubungi Anda jika diperlukan.');
    }
}
