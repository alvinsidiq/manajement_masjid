<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Informasi, Ruangan, Jadwal, Kegiatan};

class HomeController extends Controller
{
    public function index()
    {
        $infos = Informasi::query()->where('is_published', true)
            ->orderByDesc('published_at')->limit(6)->get();

        $ruangan = Ruangan::query()->orderBy('nama_ruangan')->limit(6)->get();

        $jadwal = Jadwal::query()->with('kegiatan')
            ->where('tanggal_mulai','>=', now()->startOfDay())
            ->orderBy('tanggal_mulai')->limit(6)->get();

        $kegiatan = Kegiatan::query()->where('is_archived', false)
            ->latest('created_at')->limit(6)->get();

        $stats = [
            'jadwal' => Jadwal::count(),
            'informasi' => Informasi::query()->where('is_published', true)->count(),
            'kegiatan' => Kegiatan::query()->where('is_archived', false)->count(),
            'ruangan' => Ruangan::count(),
        ];

        return view('public.home', compact('infos','ruangan','jadwal','kegiatan','stats'));
    }
}
