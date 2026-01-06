<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRuanganRequest;
use App\Http\Requests\Admin\UpdateRuanganRequest;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:admin']);
        $this->authorizeResource(Ruangan::class, 'ruangan');
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');
        $fasilitas = $request->input('f');
        $sort = $request->input('sort','created_at');
        $dir  = $request->input('dir','desc');

        $ruangan = Ruangan::query()
            ->when($q, function($qq) use ($q){
                $qq->where('nama_ruangan','like',"%$q%")
                   ->orWhere('deskripsi','like',"%$q%");
            })
            ->when($status, fn($qq) => $qq->where('status',$status))
            ->when($fasilitas, function($qq) use ($fasilitas){
                $list = collect(explode(',', $fasilitas))
                    ->map(fn($s) => trim($s))
                    ->filter()->values();
                foreach ($list as $f) {
                    $qq->whereJsonContains('fasilitas', $f);
                }
            })
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.ruangan.index', compact('ruangan','q','status','fasilitas','sort','dir'));
    }

    public function create()
    {
        return view('admin.ruangan.create');
    }

    public function store(StoreRuanganRequest $request)
    {
        $data = $request->validated();
        $data['fasilitas'] = $this->normalizeFasilitas($request->input('fasilitas'));

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->storeFoto($request->file('foto'), $data['nama_ruangan']);
        }

        Ruangan::create($data);
        return redirect()->route('admin.ruangan.index')->with('status','Ruangan dibuat.');
    }

    public function show(Ruangan $ruangan)
    {
        return view('admin.ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    public function update(UpdateRuanganRequest $request, Ruangan $ruangan)
    {
        $data = $request->validated();
        $data['fasilitas'] = $this->normalizeFasilitas($request->input('fasilitas'));

        if ($request->hasFile('foto')) {
            if ($ruangan->foto && Storage::disk('public')->exists($ruangan->foto)) {
                Storage::disk('public')->delete($ruangan->foto);
            }
            $data['foto'] = $this->storeFoto($request->file('foto'), $data['nama_ruangan']);
        }

        $ruangan->update($data);
        return redirect()->route('admin.ruangan.index')->with('status','Ruangan diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->route('admin.ruangan.index')->with('status','Ruangan dihapus.');
    }

    private function normalizeFasilitas($input): array|null
    {
        if (is_null($input) || $input === '') return null;
        if (is_array($input)) {
            return collect($input)->map(fn($s)=>trim((string)$s))->filter()->values()->all();
        }
        return collect(explode(',', (string)$input))
            ->map(fn($s)=>trim($s))
            ->filter()
            ->values()
            ->all();
    }

    private function storeFoto($file, string $nama): string
    {
        $slug = Str::slug($nama);
        $ext  = $file->getClientOriginalExtension();
        $name = $slug.'-'.uniqid().'.'.$ext;
        return $file->storeAs('ruangan', $name, 'public');
    }
}

