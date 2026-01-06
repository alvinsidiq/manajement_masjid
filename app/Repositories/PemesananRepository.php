<?php

namespace App\Repositories;

use App\Models\Pemesanan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PemesananRepository
{
    public function search(array $params): LengthAwarePaginator
    {
        $q   = $params['q'] ?? null;
        $st  = $params['status'] ?? null;
        $rid = $params['ruangan_id'] ?? null;
        $uid = $params['user_id'] ?? null;
        $df  = $params['date_from'] ?? null;
        $dt  = $params['date_to'] ?? null;
        $sort= $params['sort'] ?? 'created_at';
        $dir = $params['dir'] ?? 'desc';

        return Pemesanan::query()
            ->withJoins()
            ->when($q, function($qq) use ($q){
                $qq->where(function($w) use ($q){
                    $w->where('tujuan_pemesanan','like',"%$q%")
                      ->orWhere('catatan','like',"%$q%")
                      ->orWhereHas('user', fn($u)=>$u->where('username','like',"%$q%"))
                      ->orWhereHas('ruangan', fn($r)=>$r->where('nama_ruangan','like',"%$q%"));
                });
            })
            ->when($st, fn($qq)=>$qq->where('status',$st))
            ->when($rid, fn($qq)=>$qq->where('ruangan_id',$rid))
            ->when($uid, fn($qq)=>$qq->where('user_id',$uid))
            ->dateRange($df, $dt)
            ->orderBy($sort, $dir)
            ->paginate(12)
            ->withQueryString();
    }
}

