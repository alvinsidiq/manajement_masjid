<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RuanganFactory extends Factory
{
    public function definition(): array
    {
        $names = ['Aula Utama','Ruang Serbaguna','Perpustakaan','Kelas TPA','Ruang Rapat','Serambi Depan'];
        $nama = fake()->randomElement($names).' '.fake()->randomElement(['','Lantai 2','Barat','Timur','Utara','Selatan','Kecil','Besar','Utama','Kecil 2']);
        $fasilitas = collect(['AC','Karpet','Proyektor','Sound System','Wifi','Kipas'])
            ->shuffle()->take(rand(2,4))->values()->all();
        $sampleFotos = [
            'ruangan/aula-utama-lantai-2-692030b1dd36b.png',
            'ruangan/ruang-serbaguna-6920307c5c70c.png',
            'ruangan/perpustakaan-6920308517614.png',
            'ruangan/kelas-tpa-6920308d9fe31.png',
            'ruangan/serambi-depan-6920309717ec9.png',
            'ruangan/ruang-serbaguna-selatan-692030a976168.png',
        ];
        return [
            'nama_ruangan' => $nama,
            'kapasitas' => fake()->numberBetween(20, 300),
            'deskripsi' => fake('id_ID')->paragraph(),
            'fasilitas' => $fasilitas,
            'foto' => fake()->randomElement($sampleFotos),
            'status' => fake()->randomElement(['aktif','nonaktif','maintenance']),
            'harga' => fake()->numberBetween(75000, 500000),
        ];
    }
}
