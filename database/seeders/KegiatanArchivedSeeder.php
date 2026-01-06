<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\CarbonImmutable;

class KegiatanArchivedSeeder extends Seeder
{
    public function run(): void
    {
        if (Kegiatan::count() === 0) return;
        $adminId = User::whereHas('roles', fn($q)=>$q->where('name','admin'))->value('user_id') ?? null;
        $list = Kegiatan::inRandomOrder()->limit(5)->get();
        $now = CarbonImmutable::now('Asia/Jakarta');

        foreach ($list as $k) {
            $k->update([
                'is_archived' => true,
                'archived_at' => $now->subDays(rand(1,30))->utc(),
                'archived_by' => $adminId,
                'archive_reason' => 'Kegiatan telah selesai dan tidak aktif sementara.',
            ]);
        }
    }
}

