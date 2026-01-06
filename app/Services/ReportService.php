<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\Kegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReportService
{
    public function queryPemesanan(array $f): Builder
    {
        return Pemesanan::query()->with(['user','ruangan'])
            ->when($f['q'], fn($qq)=>$qq->where(function($w) use($f){
                $q=$f['q'];
                $w->where('tujuan_pemesanan','like',"%$q%")
                  ->orWhereHas('user',fn($u)=>$u->where('username','like',"%$q%"))
                  ->orWhereHas('ruangan',fn($r)=>$r->where('nama_ruangan','like',"%$q%"));
            }))
            ->when($f['status'], fn($qq)=>$qq->where('status',$f['status']))
            ->when($f['ruangan_id'], fn($qq)=>$qq->where('ruangan_id',$f['ruangan_id']))
            ->when($f['user_id'], fn($qq)=>$qq->where('user_id',$f['user_id']))
            ->when($f['date_from'], fn($qq)=>$qq->where('created_at','>=',Carbon::parse($f['date_from'],'Asia/Jakarta')->startOfDay()->utc()))
            ->when($f['date_to'], fn($qq)=>$qq->where('created_at','<=',Carbon::parse($f['date_to'],'Asia/Jakarta')->endOfDay()->utc()));
    }

    public function queryPenggunaanRuangan(array $f)
    {
        $rows = $this->queryPemesanan($f)
            ->selectRaw("DATE(CONVERT_TZ(created_at,'+00:00','+07:00')) as date, ruangan_id, COUNT(*) as total")
            ->groupBy('date','ruangan_id')
            ->with('ruangan')
            ->get()
            ->map(fn($r)=> (object)[
                'date' => $r->date,
                'nama_ruangan' => $r->ruangan?->nama_ruangan,
                'total' => $r->total,
            ]);
        return $rows;
    }

    public function queryAktivitasKegiatan(array $f)
    {
        return Kegiatan::query()
            ->when($f['q'], fn($qq)=>$qq->where('nama_kegiatan','like',"%{$f['q']}%"))
            ->when($f['date_from'], fn($qq)=>$qq->where('created_at','>=',Carbon::parse($f['date_from'],'Asia/Jakarta')->startOfDay()->utc()))
            ->when($f['date_to'], fn($qq)=>$qq->where('created_at','<=',Carbon::parse($f['date_to'],'Asia/Jakarta')->endOfDay()->utc()))
            ->get()
            ->map(fn($k)=> (object) [
                'date' => $k->created_at->timezone('Asia/Jakarta')->format('Y-m-d'),
                'jenis_kegiatan' => $k->jenis_kegiatan->value,
                'nama_kegiatan' => $k->nama_kegiatan,
                'penanggung_jawab' => $k->penanggung_jawab,
            ]);
    }
}

