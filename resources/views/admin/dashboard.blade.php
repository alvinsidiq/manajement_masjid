@extends('layouts.admin', ['pageTitle'=>'Dashboard Admin'])
@section('content')
@php
  $cards = [
    [
      'label' => 'Total Pengguna',
      'value' => $stats['users'] ?? 0,
      'link' => route('admin.users.index'),
      'link_label' => 'Kelola pengguna',
    ],
    [
      'label' => 'Total Ruangan',
      'value' => $stats['ruangan'] ?? 0,
      'link' => route('admin.ruangan.index'),
      'link_label' => 'Kelola ruangan',
    ],
    [
      'label' => 'Total Kegiatan',
      'value' => $stats['kegiatan'] ?? 0,
      'link' => route('admin.kegiatan.index'),
      'link_label' => 'Kelola kegiatan',
    ],
    [
      'label' => 'Menunggu Verifikasi',
      'value' => $stats['pemesanan_waiting'] ?? 0,
      'link' => route('admin.pemesanan.index', ['status' => 'menunggu_verifikasi']),
      'link_label' => 'Informasi booking',
    ],
    [
      'label' => 'Jenis Laporan',
      'value' => $stats['report_types'] ?? 0,
      'link' => route('admin.reports.index', ['report' => 'pemesanan']),
      'link_label' => 'Buka laporan',
    ],
  ];
@endphp

<div class="grid md:grid-cols-3 gap-4">
  @foreach($cards as $card)
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="text-sm text-gray-500">{{ $card['label'] }}</div>
      <div class="mt-2 text-3xl font-semibold">{{ number_format($card['value']) }}</div>
      <div class="mt-3">
        <a class="text-sm text-blue-600 hover:underline" href="{{ $card['link'] }}">{{ $card['link_label'] }}</a>
      </div>
    </div>
  @endforeach
</div>
@endsection
