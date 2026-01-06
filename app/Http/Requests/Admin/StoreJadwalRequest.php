<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\JadwalService;

class StoreJadwalRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        return [
            'kegiatan_id' => ['required','integer','exists:kegiatan,kegiatan_id'],
            'ruangan_id'  => ['nullable','integer','exists:ruangan,ruangan_id'],
            'tanggal_mulai' => ['required','date'],
            'tanggal_selesai'=> ['required','date','after:tanggal_mulai'],
            'status' => ['required','in:rutin,berkala,khusus'],
            'catatan'=> ['nullable','string','max:2000'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($v){
            try {
                $ruanganId = $this->filled('ruangan_id') ? (int)$this->input('ruangan_id') : null;
                app(JadwalService::class)->assertNoConflicts(
                    $ruanganId,
                    (string) $this->string('tanggal_mulai'),
                    (string) $this->string('tanggal_selesai')
                );
            } catch (\InvalidArgumentException $e) {
                $v->errors()->add('tanggal_mulai', $e->getMessage());
            }
        });
    }
}

