<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganPriceSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'Aula Utama' => 500000,
            'Ruang Serbaguna' => 250000,
            'Perpustakaan' => 150000,
            'Kelas TPA' => 100000,
            'Serambi Depan' => 80000,
        ];

        foreach ($defaults as $nama => $harga) {
            Ruangan::where('nama_ruangan', $nama)->update(['harga' => $harga]);
        }

        Ruangan::whereNull('harga')->update(['harga' => 100000]);
    }
}
