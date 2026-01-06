<?php

namespace App\Exports\Reports;

use App\Models\Pemesanan;

if (interface_exists(\Maatwebsite\Excel\Concerns\FromCollection::class)) {
    class PemesananExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
    {
        public function __construct(private $rows) {}

        public function collection()
        { return $this->rows; }

        public function headings(): array
        {
            return ['ID','Tanggal','Pemesan','Email','Ruangan','Tujuan','Status'];
        }

        public function map($p): array
        {
            return [
                $p->pemesanan_id,
                $p->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
                $p->user->username,
                $p->user->email,
                $p->ruangan->nama_ruangan,
                $p->tujuan_pemesanan,
                str($p->status->value)->replace('_',' ')->title(),
            ];
        }
    }
} else {
    // Fallback stub to avoid autoload errors when excel package not installed
    class PemesananExport {}
}

