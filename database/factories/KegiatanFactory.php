<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KegiatanFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Kajian Rutin Ahad Pagi','TPA Sore','Kursus Tahsin','Pelatihan Mualaf','Bakti Sosial','Khataman Qur\'an',
        ];
        $sampleFotos = [
            'kegiatan/kajian-rutin-ahad-pagi-69202a906dcb8.png',
            'kegiatan/kajian-rutin-ahad-pagi-69202ab438d01.png',
            'kegiatan/tpa-sore-69202ac125da6.png',
            'kegiatan/tpa-sore-694e983c44c1d.png',
        ];
        return [
            'nama_kegiatan' => fake('id_ID')->randomElement($names),
            'deskripsi' => fake('id_ID')->paragraph(),
            'jenis_kegiatan' => fake()->randomElement(['rutin','berkala','khusus']),
            'penanggung_jawab' => fake('id_ID')->name(),
            'no_telephone' => '+62'.fake()->numerify('8#########'),
            'foto' => fake()->randomElement($sampleFotos),
        ];
    }
}
