<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuanganRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        return [
            'nama_ruangan' => ['required','string','max:120','unique:ruangan,nama_ruangan'],
            'deskripsi'    => ['nullable','string'],
            'fasilitas'    => ['nullable'],
            'foto'         => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'status'       => ['required','in:aktif,nonaktif,maintenance'],
            'harga'        => ['required','numeric','min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan sudah ada.',
            'foto.image'            => 'File foto harus berupa gambar.',
            'status.in'             => 'Status tidak valid.',
        ];
    }
}
