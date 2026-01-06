<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\BookingService;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->hasAnyRole(['admin','takmir']) ?? false; }

    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,user_id'],
            'ruangan_id' => ['required','integer','exists:ruangan,ruangan_id'],
            'hari_tanggal' => ['required','date'],
            'jam' => ['required','date_format:H:i'],
            'status' => ['nullable','in:hold,expired,submitted,cancelled'],
            'hold_expires_at' => ['nullable','date','after:now'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($v){
            try {
                app(BookingService::class)->assertNoConflicts(
                    (int) $this->input('ruangan_id'),
                    (string) $this->string('hari_tanggal'),
                    (string) $this->string('jam')
                );
            } catch (\InvalidArgumentException $e) {
                $v->errors()->add('hari_tanggal', $e->getMessage());
            }
        });
    }
}

