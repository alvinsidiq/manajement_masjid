<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportFilterRequest;
use App\Services\ReportService;
use App\Exports\Reports\{PemesananExport, PenggunaanRuanganExport, AktivitasKegiatanExport};
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function __construct(private ReportService $svc)
    {
        $this->middleware(['auth','verified','active','role:admin']);
    }

    protected function auditExport(string $report, string $format, array $f, int $count = 0): void
    {
        try {
            if (class_exists(\App\Models\AuditLog::class)) {
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'action'  => 'reports.export',
                    'ip'      => request()->ip(),
                    'user_agent' => substr((string)request()->userAgent(), 0, 255),
                    'context' => [
                        'report' => $report,
                        'format' => $format,
                        'filters' => $f,
                        'count' => $count,
                    ],
                ]);
            }
        } catch (\Throwable $e) {}
    }

    public function index(ReportFilterRequest $request)
    {
        $f = $request->normalized();
        $report = $f['report'] ?? 'pemesanan';
        $format = $f['format'] ?? 'html';

        if ($report === 'pemesanan') {
            $query = $this->svc->queryPemesanan($f)->orderBy($f['sort'],$f['dir']);
            $rows = $format==='html' ? $query->paginate(15)->withQueryString() : $query->get();

            if ($format==='pdf') {
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pemesanan-pdf', ['rows'=>$rows,'f'=>$f]);
                    $this->auditExport($report,'pdf',$f,$rows->count());
                    return $pdf->download('laporan-pemesanan.pdf');
                }
                $this->auditExport($report,'pdf',$f,$rows->count());
                return response()->view('reports.pemesanan-pdf', ['rows'=>$rows,'f'=>$f]);
            }
            if ($format==='excel') {
                if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                    $this->auditExport($report,'excel',$f,$rows->count());
                    return \Maatwebsite\Excel\Facades\Excel::download(new PemesananExport($rows), 'laporan-pemesanan.xlsx');
                }
                $this->auditExport($report,'excel',$f,$rows->count());
                $csv = $this->toCsv(['Tanggal','Pemesan','Email','Ruangan','Tujuan','Status'], $rows->map(function($p){
                    return [
                        $p->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
                        $p->user->username,
                        $p->user->email,
                        $p->ruangan->nama_ruangan,
                        $p->tujuan_pemesanan,
                        str($p->status->value)->replace('_',' ')->title(),
                    ];
                })->all());
                return $this->csvResponse($csv, 'laporan-pemesanan.csv');
            }
            return view('admin.reports.pemesanan', ['rows'=>$rows,'f'=>$f]);
        }

        if ($report === 'penggunaan-ruangan') {
            $rows = $this->svc->queryPenggunaanRuangan($f);
            if ($format==='pdf') {
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.penggunaan-ruangan-pdf', ['rows'=>$rows,'f'=>$f]);
                    $this->auditExport($report,'pdf',$f, count($rows));
                    return $pdf->download('laporan-penggunaan-ruangan.pdf');
                }
                $this->auditExport($report,'pdf',$f, count($rows));
                return response()->view('reports.penggunaan-ruangan-pdf', ['rows'=>$rows,'f'=>$f]);
            }
            if ($format==='excel') {
                if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                    $this->auditExport($report,'excel',$f, count($rows));
                    return \Maatwebsite\Excel\Facades\Excel::download(new PenggunaanRuanganExport($rows), 'laporan-penggunaan-ruangan.xlsx');
                }
                $this->auditExport($report,'excel',$f, count($rows));
                $csv = $this->toCsv(['Tanggal','Ruangan','Jumlah Pemesanan'], array_map(fn($r)=>[
                    \Carbon\Carbon::parse($r->date)->format('d/m/Y'), $r->nama_ruangan, $r->total
                ], $rows->all()));
                return $this->csvResponse($csv, 'laporan-penggunaan-ruangan.csv');
            }
            return view('admin.reports.penggunaan-ruangan', ['rows'=>$rows,'f'=>$f]);
        }

        // aktivitas-kegiatan
        $rows = $this->svc->queryAktivitasKegiatan($f);
        if ($format==='pdf') {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.aktivitas-kegiatan-pdf', ['rows'=>$rows,'f'=>$f]);
                $this->auditExport($report,'pdf',$f, count($rows));
                return $pdf->download('laporan-aktivitas-kegiatan.pdf');
            }
            $this->auditExport($report,'pdf',$f, count($rows));
            return response()->view('reports.aktivitas-kegiatan-pdf', ['rows'=>$rows,'f'=>$f]);
        }
        if ($format==='excel') {
            if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                $this->auditExport($report,'excel',$f, count($rows));
                return \Maatwebsite\Excel\Facades\Excel::download(new AktivitasKegiatanExport($rows), 'laporan-aktivitas-kegiatan.xlsx');
            }
            $this->auditExport($report,'excel',$f, count($rows));
            $csv = $this->toCsv(['Tanggal','Jenis','Nama Kegiatan','Penanggung Jawab'], array_map(fn($r)=>[
                \Carbon\Carbon::parse($r->date)->format('d/m/Y'), ucfirst($r->jenis_kegiatan), $r->nama_kegiatan, $r->penanggung_jawab
            ], $rows->all()));
            return $this->csvResponse($csv, 'laporan-aktivitas-kegiatan.csv');
        }
        return view('admin.reports.aktivitas-kegiatan', ['rows'=>$rows,'f'=>$f]);
    }

    protected function toCsv(array $headings, array $rows): string
    {
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $headings);
        foreach ($rows as $r) { fputcsv($fh, $r); }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
        return (string)$csv;
    }

    protected function csvResponse(string $csv, string $filename)
    {
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}

