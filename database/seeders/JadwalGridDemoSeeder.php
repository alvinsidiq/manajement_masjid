<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Carbon\CarbonImmutable;
use App\Models\{Jadwal, Kegiatan, Ruangan};

class JadwalGridDemoSeeder extends Seeder
{
    public function run(): void
    {
        $tz = 'Asia/Jakarta';
        $now = CarbonImmutable::now($tz);

        /** @var Collection<int,\App\Models\Ruangan> $rooms */
        $rooms = Ruangan::query()->take(4)->get();
        if ($rooms->isEmpty()) {
            $rooms = Ruangan::factory()->count(4)->create([
                'status' => 'tersedia',
            ]);
        }

        $layout = collect([
            [
                'title' => 'Kajian Tafsir Pagi',
                'days_after' => 2,
                'hour' => 5,
                'minute' => 30,
                'duration_hours' => 2,
                'status' => 'rutin',
                'note' => 'Kajian umum setiap pekan.',
                'deskripsi' => 'Kajian tafsir harian bersama ustadz tetap masjid.',
            ],
            [
                'title' => 'Kelas Tahsin Akhwat',
                'days_after' => 4,
                'hour' => 13,
                'minute' => 30,
                'duration_hours' => 2,
                'status' => 'berkala',
                'note' => 'Khusus pendaftar.',
                'deskripsi' => 'Perbaikan bacaan Al-Quran untuk jamaah akhwat.',
            ],
            [
                'title' => 'Pelatihan Multimedia Remaja',
                'days_after' => 6,
                'hour' => 19,
                'minute' => 0,
                'duration_hours' => 2,
                'status' => 'khusus',
                'note' => 'Disertai praktek produksi konten.',
                'deskripsi' => 'Pembekalan tim media masjid.',
            ],
            [
                'title' => 'Majelis Dzikir Bulanan',
                'days_after' => 9,
                'hour' => 20,
                'minute' => 0,
                'duration_hours' => 2,
                'status' => 'rutin',
                'note' => 'Terbuka untuk jamaah umum.',
                'deskripsi' => 'Dzikir dan doa bersama setiap awal bulan.',
            ],
            [
                'title' => 'Rapat Takmir Triwulan',
                'days_after' => 12,
                'hour' => 15,
                'minute' => 0,
                'duration_hours' => 2,
                'status' => 'khusus',
                'note' => 'Agenda evaluasi program.',
                'deskripsi' => 'Pembahasan agenda internal pengurus.',
            ],
            [
                'title' => 'Pengajian Remaja Malam Ahad',
                'days_after' => 15,
                'hour' => 19,
                'minute' => 30,
                'duration_hours' => 2,
                'status' => 'rutin',
                'note' => 'Fokus materi akhlak.',
                'deskripsi' => 'Sharing dan kajian santai remaja masjid.',
            ],
        ]);

        $roomsCount = max(1, $rooms->count());

        $layout->each(function (array $item, int $index) use ($rooms, $roomsCount, $now) {
            $kegiatan = Kegiatan::factory()->create([
                'nama_kegiatan' => $item['title'],
                'deskripsi' => $item['deskripsi'],
                'jenis_kegiatan' => $item['status'],
                'penanggung_jawab' => 'Takmir Masjid',
                'no_telephone' => '08120000000',
            ]);

            $startLocal = $now->addDays($item['days_after'])->setTime($item['hour'], $item['minute']);
            $endLocal = $startLocal->addHours($item['duration_hours']);

            Jadwal::create([
                'kegiatan_id' => $kegiatan->kegiatan_id,
                'ruangan_id' => optional($rooms->get($index % $roomsCount))->ruangan_id,
                'tanggal_mulai' => $startLocal->setTimezone('UTC'),
                'tanggal_selesai' => $endLocal->setTimezone('UTC'),
                'status' => $item['status'],
                'catatan' => $item['note'],
            ]);
        });
    }
}
