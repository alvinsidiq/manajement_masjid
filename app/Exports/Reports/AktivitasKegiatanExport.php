<?php

namespace App\Exports\Reports;

if (interface_exists(\Maatwebsite\Excel\Concerns\FromCollection::class)) {
    class AktivitasKegiatanExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
    {
        public function __construct(private $rows) {}
        public function collection() { return $this->rows; }
        public function headings(): array { return ['Tanggal','Jenis','Nama Kegiatan','Penanggung Jawab']; }
        public function map($row): array { return [$row->date, ucfirst($row->jenis_kegiatan), $row->nama_kegiatan, $row->penanggung_jawab]; }
    }
} else {
    class AktivitasKegiatanExport {}
}

