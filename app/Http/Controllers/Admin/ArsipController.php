<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArsipRequest;
use App\Http\Requests\Admin\UpdateArsipRequest;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:admin']);
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $sort = $request->input('sort','created_at');
        $dir = $request->input('dir','desc');

        $items = Arsip::query()
            ->when($q, function($qq) use ($q){
                $qq->where(function($w) use ($q){
                    $w->where('judul','like',"%$q%")
                      ->orWhere('deskripsi','like',"%$q%");
                });
            })
            ->orderBy($sort, $dir)
            ->paginate(12)
            ->withQueryString();

        return view('admin.arsip.index', compact('items','q','sort','dir'));
    }

    public function create()
    {
        return view('admin.arsip.create');
    }

    public function store(StoreArsipRequest $request)
    {
        $data = $request->validated();
        $data['dokumen'] = $this->storeFile($request->file('dokumen'), $data['judul']);
        $data['uploaded_by'] = auth()->id();

        Arsip::create($data);

        return redirect()->route('admin.arsip.index')->with('status','Arsip disimpan.');
    }

    public function show(Arsip $arsip)
    {
        return view('admin.arsip.show', compact('arsip'));
    }

    public function edit(Arsip $arsip)
    {
        return view('admin.arsip.edit', compact('arsip'));
    }

    public function update(UpdateArsipRequest $request, Arsip $arsip)
    {
        $data = $request->validated();
        if ($request->hasFile('dokumen')) {
            if ($arsip->dokumen && Storage::disk('public')->exists($arsip->dokumen)) {
                Storage::disk('public')->delete($arsip->dokumen);
            }
            $data['dokumen'] = $this->storeFile($request->file('dokumen'), $data['judul']);
        }

        $arsip->update($data);

        return redirect()->route('admin.arsip.index')->with('status','Arsip diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        if ($arsip->dokumen && Storage::disk('public')->exists($arsip->dokumen)) {
            Storage::disk('public')->delete($arsip->dokumen);
        }
        $arsip->delete();
        return redirect()->route('admin.arsip.index')->with('status','Arsip dihapus.');
    }

    private function storeFile($file, string $judul): string
    {
        $slug = Str::slug($judul);
        $ext  = $file->getClientOriginalExtension();
        $name = $slug.'-arsip-'.uniqid().'.'.$ext;
        return $file->storeAs('arsip', $name, 'public');
    }
}
