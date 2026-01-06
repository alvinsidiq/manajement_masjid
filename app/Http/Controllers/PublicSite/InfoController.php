<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfoController extends Controller
{
    private array $pages = [
        ['slug' => 'profil-masjid', 'title' => 'Profil Masjid', 'content' => 'Sejarah singkat dan visi misi.'],
        ['slug' => 'kegiatan-utama', 'title' => 'Kegiatan Utama', 'content' => 'Kajian rutin, TPA, dan agenda pekanan.'],
    ];

    public function index(Request $request)
    {
        $q = $request->get('q');
        $pages = collect($this->pages)
            ->when($q, fn($c) => $c->filter(fn($p) => Str::contains(Str::lower($p['title'].' '.$p['content']), Str::lower((string) $q))))
            ->values();

        return view('public.info.index', ['pages' => $pages, 'q' => $q]);
    }

    public function create() { abort(403); }
    public function store(Request $r) { abort(403); }
    public function show(string $slug)
    {
        $page = collect($this->pages)->firstWhere('slug', $slug);
        abort_if(!$page, 404);
        return view('public.info.show', compact('page'));
    }
    public function edit(string $id) { abort(403); }
    public function update(Request $r, string $id) { abort(403); }
    public function destroy(string $id) { abort(403); }
}

