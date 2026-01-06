<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    { return auth()->user()?->hasRole('admin') ?? false; }

    public function rules(): array
    {
        return [
            'q' => ['nullable','string','max:120'],
            'status' => ['nullable','in:menunggu_verifikasi,diterima,ditolak,dibatalkan,selesai'],
            'ruangan_id' => ['nullable','integer','min:1'],
            'user_id' => ['nullable','integer','min:1'],
            'date_from' => ['nullable','date'],
            'date_to' => ['nullable','date','after_or_equal:date_from'],
            'format' => ['nullable','in:html,pdf,excel'],
            'report' => ['required','in:pemesanan,penggunaan-ruangan,aktivitas-kegiatan'],
            'sort' => ['nullable','in:created_at,status,ruangan_id,user_id'],
            'dir'  => ['nullable','in:asc,desc'],
        ];
    }

    public function normalized(): array
    {
        $v = $this->validated();
        return [
            'q' => $v['q'] ?? null,
            'status' => $v['status'] ?? null,
            'ruangan_id' => $v['ruangan_id'] ?? null,
            'user_id' => $v['user_id'] ?? null,
            'date_from' => $v['date_from'] ?? null,
            'date_to' => $v['date_to'] ?? null,
            'format' => $v['format'] ?? 'html',
            'report' => $v['report'],
            'sort' => $v['sort'] ?? 'created_at',
            'dir'  => $v['dir'] ?? 'desc',
        ];
    }
}

