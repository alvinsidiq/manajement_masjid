<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\InformasiFilterRequest;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index(InformasiFilterRequest $request)
    {
        $q = $request->input('q');
        $month = $request->integer('month');
        $year = $request->integer('year');

        $items = Informasi::query()
            ->when(!$request->user() || !$request->user()->hasAnyRole(['admin','takmir']), function($qq){
                $qq->where('is_published', true);
            })
            ->when($q, fn($qq)=>$qq->where(function($w) use ($q){
                $w->where('judul','like',"%$q%")
                  ->orWhere('ringkasan','like',"%$q%")
                  ->orWhere('isi','like',"%$q%");
            }))
            ->when($month, fn($qq)=>$qq->whereMonth('published_at', $month))
            ->when($year, fn($qq)=>$qq->whereYear('published_at', $year))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.informasi.index', compact('items','q','month','year'));
    }

    public function show(Request $request, string $slug)
    {
        $info = Informasi::where('slug',$slug)->firstOrFail();
        if (!$info->is_published && !($request->user() && $request->user()->hasAnyRole(['admin','takmir']))) {
            abort(404);
        }
        return view('public.informasi.show', compact('info'));
    }
}

