<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_ruangan' => 'Aula Utama',
                'kapasitas' => 300,
                'deskripsi' => 'Ruang utama untuk khutbah, kajian umum, dan acara besar.',
                'fasilitas' => ['AC','Karpet','Sound System','Proyektor'],
                'status' => 'aktif',
                'harga' => 500000,
                'foto' => 'ruangan/aula-utama-lantai-2-692030b1dd36b.png',
            ],
            [
                'nama_ruangan' => 'Ruang Serbaguna',
                'kapasitas' => 120,
                'deskripsi' => 'Cocok untuk rapat kecil, pelatihan, dan pertemuan komunitas.',
                'fasilitas' => ['Kipas','Karpet','Wifi'],
                'status' => 'aktif',
                'harga' => 250000,
                'foto' => 'ruangan/ruang-serbaguna-6920307c5c70c.png',
            ],
            [
                'nama_ruangan' => 'Perpustakaan',
                'kapasitas' => 60,
                'deskripsi' => 'Koleksi buku-buku islami dan tempat baca.',
                'fasilitas' => ['Kipas','Wifi'],
                'status' => 'maintenance',
                'harga' => 150000,
                'foto' => 'ruangan/perpustakaan-6920308517614.png',
            ],
            [
                'nama_ruangan' => 'Kelas TPA',
                'kapasitas' => 40,
                'deskripsi' => 'Ruang belajar anak-anak dengan meja kecil.',
                'fasilitas' => ['Kipas','Karpet','Papan Tulis'],
                'status' => 'aktif',
                'harga' => 100000,
                'foto' => 'ruangan/kelas-tpa-6920308d9fe31.png',
            ],
            [
                'nama_ruangan' => 'Serambi Depan',
                'kapasitas' => 80,
                'deskripsi' => 'Area semi-outdoor untuk istirahat jamaah.',
                'fasilitas' => ['Karpet'],
                'status' => 'nonaktif',
                'harga' => 80000,
                'foto' => 'ruangan/serambi-depan-6920309717ec9.png',
            ],
        ];

        foreach ($data as $d) {
            Ruangan::updateOrCreate(['nama_ruangan'=>$d['nama_ruangan']], $d);
        }

        // Tambahan acak, hindari bentrok unik dengan firstOrCreate
        for ($i = 0; $i < 10; $i++) {
            $payload = Ruangan::factory()->make()->toArray();
            Ruangan::firstOrCreate(['nama_ruangan' => $payload['nama_ruangan']], $payload);
        }
    }
}
