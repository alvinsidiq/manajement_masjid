<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRuanganRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        $id = $this->route('ruangan')->ruangan_id ?? null;
        return [
            'nama_ruangan' => ['required','string','max:120','unique:ruangan,nama_ruangan,'.$id.',ruangan_id'],
            'kapasitas'    => ['required','integer','min:1'],
            'deskripsi'    => ['nullable','string'],
            'fasilitas'    => ['nullable'],
            'foto'         => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'status'       => ['required','in:aktif,nonaktif,maintenance'],
            'harga'        => ['required','numeric','min:0'],
        ];
    }
}
