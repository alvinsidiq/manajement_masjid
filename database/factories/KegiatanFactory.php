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
        return [
            'nama_kegiatan' => fake('id_ID')->randomElement($names),
            'deskripsi' => fake('id_ID')->paragraph(),
            'jenis_kegiatan' => fake()->randomElement(['rutin','berkala','khusus']),
            'penanggung_jawab' => fake('id_ID')->name(),
            'no_telephone' => '+62'.fake()->numerify('8#########'),
        ];
    }
}

