<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\JadwalFilterRequest;
use App\Models\{Jadwal, Pemesanan};
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class JadwalController extends Controller
{
    public function index(JadwalFilterRequest $request)
    {
        $tz = 'Asia/Jakarta';
        $now = now($tz);
        $validated = $request->validated();

        $month = (int) ($validated['month'] ?? $now->month);
        $year = (int) ($validated['year'] ?? $now->year);
        $view = $validated['view'] ?? 'calendar';
        $q = trim((string) ($validated['q'] ?? ''));

        $yearMin = 2000;
        $yearMax = (int) date('Y');

        $month = max(1, min(12, $month));
        $year = max($yearMin, min($yearMax, $year));
        if (!in_array($view, ['calendar', 'list'], true)) {
            $view = 'calendar';
        }

        $monthStart = CarbonImmutable::create($year, $month, 1, 0, 0, 0, $tz)->startOfMonth();
        $monthEnd = $monthStart->endOfMonth();
        $calendarStart = $monthStart->startOfWeek(CarbonInterface::SUNDAY);
        $calendarEnd = $monthEnd->endOfWeek(CarbonInterface::SATURDAY);

        $calendarStartUtc = $calendarStart->setTimezone('UTC');
        $calendarEndUtc = $calendarEnd->setTimezone('UTC');

        $days = [];
        for ($cursor = $calendarStart; $cursor->lte($calendarEnd); $cursor = $cursor->addDay()) {
            $days[$cursor->toDateString()] = [
                'date' => $cursor,
                'inMonth' => $cursor->month === $month,
                'events' => collect(),
            ];
        }

        $jadwalQuery = Jadwal::query()
            ->with(['kegiatan', 'ruangan'])
            ->where('tanggal_mulai', '<=', $calendarEndUtc)
            ->where('tanggal_selesai', '>=', $calendarStartUtc);

        if ($q !== '') {
            $jadwalQuery->where(function ($query) use ($q) {
                $query->whereHas('kegiatan', fn($k) => $k->where('nama_kegiatan', 'like', "%{$q}%"))
                    ->orWhereHas('ruangan', fn($r) => $r->where('nama_ruangan', 'like', "%{$q}%"))
                    ->orWhere('catatan', 'like', "%{$q}%");
            });
        }

        $jadwalItems = $jadwalQuery->orderBy('tanggal_mulai')->get();

        $acceptedQuery = Pemesanan::query()
            ->with(['ruangan', 'booking', 'jadwal.kegiatan', 'jadwal.ruangan'])
            ->where('status', 'diterima')
            ->where(function ($query) use ($calendarStartUtc, $calendarEndUtc) {
                $query->whereHas('booking', fn($booking) => $booking->whereBetween('hari_tanggal', [$calendarStartUtc, $calendarEndUtc]))
                    ->orWhereHas('jadwal', fn($jadwal) => $jadwal
                        ->where('tanggal_mulai', '<=', $calendarEndUtc)
                        ->where('tanggal_selesai', '>=', $calendarStartUtc));
            });

        if ($q !== '') {
            $acceptedQuery->where(function ($query) use ($q) {
                $query->where('tujuan_pemesanan', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%")
                    ->orWhereHas('ruangan', fn($r) => $r->where('nama_ruangan', 'like', "%{$q}%"))
                    ->orWhereHas('jadwal.kegiatan', fn($k) => $k->where('nama_kegiatan', 'like', "%{$q}%"));
            });
        }

        $accepted = $acceptedQuery->get();

        $matchesSearch = function (array $payload) use ($q): bool {
            if ($q === '') {
                return true;
            }

            $needle = Str::lower($q);
            $haystack = Str::of(collect($payload)
                ->filter(fn($value) => filled($value))
                ->map(fn($value) => is_scalar($value) ? (string) $value : (string) $value)
                ->implode(' '))->lower();

            return Str::contains($haystack, $needle);
        };

        $allEvents = collect();

        foreach ($jadwalItems as $jadwal) {
            $start = CarbonImmutable::make($jadwal->tanggal_mulai)?->setTimezone($tz);
            $end = CarbonImmutable::make($jadwal->tanggal_selesai)?->setTimezone($tz);
            if (!$start || !$end) {
                continue;
            }

            $key = $start->toDateString();
            if (!isset($days[$key])) {
                continue;
            }

            $title = $jadwal->kegiatan?->nama_kegiatan ?? 'Kegiatan #'.$jadwal->jadwal_id;
            $ruangan = $jadwal->ruangan?->nama_ruangan;
            $time = $start->format('H:i').'–'.$end->format('H:i');
            $note = $jadwal->catatan;

            if (!$matchesSearch([$title, $ruangan, $time, $note])) {
                continue;
            }

            $event = [
                'type' => 'jadwal',
                'label' => 'Jadwal Kegiatan',
                'title' => $title,
                'ruangan' => $ruangan,
                'time' => $time,
                'note' => $note,
                'status' => $jadwal->status?->value,
                'start' => $start,
                'end' => $end,
                'source_id' => $jadwal->jadwal_id,
            ];

            $days[$key]['events']->push($event);
            $allEvents->push($event);
        }

        foreach ($accepted as $p) {
            $title = 'Booked: '.($p->ruangan->nama_ruangan ?? 'Ruangan');
            $note = $p->tujuan_pemesanan;
            $ruangan = $p->ruangan->nama_ruangan ?? null;
            $start = null;
            $end = null;
            $time = null;

            if ($p->booking) {
                $start = CarbonImmutable::make($p->booking->hari_tanggal)?->setTimezone($tz);
                $end = $start?->addHour();
                $time = $start?->format('H:i');
            } elseif ($p->jadwal) {
                $start = CarbonImmutable::make($p->jadwal->tanggal_mulai)?->setTimezone($tz);
                $end = CarbonImmutable::make($p->jadwal->tanggal_selesai)?->setTimezone($tz);
                $time = $start && $end ? $start->format('H:i').'–'.$end->format('H:i') : null;
                if ($p->jadwal->kegiatan?->nama_kegiatan) {
                    $title = 'Booked: '.$p->jadwal->kegiatan->nama_kegiatan;
                }
            }

            if (!$start) {
                continue;
            }

            $key = $start->toDateString();
            if (!isset($days[$key])) {
                continue;
            }

            if (!$matchesSearch([$title, $ruangan, $time, $note])) {
                continue;
            }

            $event = [
                'type' => 'booking',
                'label' => 'Pemesanan Diterima',
                'title' => $title,
                'ruangan' => $ruangan,
                'time' => $time ?? $start->format('H:i'),
                'note' => $note,
                'status' => $p->status?->value ?? 'diterima',
                'start' => $start,
                'end' => $end ?? $start->addHour(),
                'source_id' => $p->pemesanan_id,
            ];

            $days[$key]['events']->push($event);
            $allEvents->push($event);
        }

        foreach ($days as $key => $day) {
            $days[$key]['events'] = $day['events']->sortBy(function ($event) {
                /** @var CarbonInterface|null $start */
                $start = $event['start'] ?? null;
                return $start ? $start->getTimestamp() : PHP_INT_MAX;
            })->values();
        }

        $orderedDays = collect($days)->map(function ($day, $key) {
            return [
                'date' => $day['date'],
                'key' => $key,
                'inMonth' => $day['inMonth'],
                'events' => $day['events']->all(),
            ];
        })->values();

        $weeks = $orderedDays->chunk(7)->map(fn($week) => $week->all())->all();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $monthLabel = $monthNames[$month] ?? 'Bulan';

        $yearOptions = collect(range(max($yearMin, $year - 2), min($yearMax, $year + 2)))
            ->push($year)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $sortedEvents = $allEvents->sortBy(function ($event) {
            /** @var CarbonInterface|null $start */
            $start = $event['start'] ?? null;
            $timestamp = $start ? $start->getTimestamp() : PHP_INT_MAX;
            return sprintf('%015d-%s', $timestamp, $event['title']);
        })->values();

        $listItems = $sortedEvents
            ->filter(fn($event) => $event['start'] && $event['start']->month === $month && $event['start']->year === $year)
            ->map(function ($event) {
                return [
                    'date' => $event['start'],
                    'type' => $event['type'],
                    'label' => $event['label'],
                    'title' => $event['title'],
                    'ruangan' => $event['ruangan'],
                    'time' => $event['time'],
                    'note' => $event['note'],
                    'status' => $event['status'],
                    'start' => $event['start'],
                    'end' => $event['end'],
                ];
            })
            ->values();

        $kegiatanCount = $allEvents->where('type', 'jadwal')->count();
        $pemesananCount = $allEvents->where('type', 'booking')->count();

        return view('public.jadwal.index', [
            'month' => $month,
            'year' => $year,
            'view' => $view,
            'q' => $q,
            'from' => $monthStart->toDateString(),
            'to' => $monthEnd->toDateString(),
            'days' => $orderedDays->all(),
            'listItems' => $listItems,
            'monthLabel' => $monthLabel,
            'monthNames' => $monthNames,
            'yearOptions' => $yearOptions,
            'yearMin' => $yearMin,
            'yearMax' => $yearMax,
            'weeks' => $weeks,
            'listEvents' => $listItems,
            'kegiatanCount' => $kegiatanCount,
            'pemesananCount' => $pemesananCount,
            'tz' => $tz,
        ]);
    }
}
