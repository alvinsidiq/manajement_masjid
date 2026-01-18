<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'nama_kegiatan' => 'Kajian Rutin Ahad Pagi',
                'deskripsi' => 'Kajian tafsir tematik setiap Ahad pagi selepas Subuh.',
                'jenis_kegiatan' => 'rutin',
                'penanggung_jawab' => 'Ust. Ahmad',
                'no_telephone' => '+628123456789',
                'foto' => 'kegiatan/kajian-rutin-ahad-pagi-69202a906dcb8.png',
            ],
            [
                'nama_kegiatan' => 'TPA Sore',
                'deskripsi' => 'Taman Pendidikan Al-Qur\'an untuk anak-anak setiap hari kerja pukul 16.00-17.30.',
                'jenis_kegiatan' => 'rutin',
                'penanggung_jawab' => 'Ustadzah Siti',
                'no_telephone' => '+628987654321',
                'foto' => 'kegiatan/tpa-sore-69202ac125da6.png',
            ],
            [
                'nama_kegiatan' => 'Bakti Sosial',
                'deskripsi' => 'Pembagian paket sembako untuk dhuafa dan lansia.',
                'jenis_kegiatan' => 'khusus',
                'penanggung_jawab' => 'Panitia Sosial',
                'no_telephone' => '+6282233445566',
                'foto' => 'kegiatan/kajian-rutin-ahad-pagi-69202ab438d01.png',
            ],
            [
                'nama_kegiatan' => 'Pelatihan Mualaf',
                'deskripsi' => 'Pendampingan dasar-dasar ibadah dan akidah untuk mualaf.',
                'jenis_kegiatan' => 'berkala',
                'penanggung_jawab' => 'Tim Dakwah',
                'no_telephone' => '+628112223334',
                'foto' => 'kegiatan/tpa-sore-694e983c44c1d.png',
            ],
        ];

        foreach ($rows as $d) {
            Kegiatan::updateOrCreate(
                ['nama_kegiatan' => $d['nama_kegiatan']],
                $d
            );
        }

        Kegiatan::factory()->count(10)->create();
    }
}
