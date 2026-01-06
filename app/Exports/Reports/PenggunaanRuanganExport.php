<?php

namespace App\Exports\Reports;

if (interface_exists(\Maatwebsite\Excel\Concerns\FromCollection::class)) {
    class PenggunaanRuanganExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
    {
        public function __construct(private $rows) {}
        public function collection() { return $this->rows; }
        public function headings(): array { return ['Tanggal','Ruangan','Jumlah Pemesanan']; }
        public function map($row): array { return [$row->date, $row->nama_ruangan, $row->total]; }
    }
} else {
    class PenggunaanRuanganExport {}
}

