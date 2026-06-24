@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
        <div>
            <div class="hero-kicker">Dashboard Admin</div>
            <h1 class="h4 mb-1">Ringkasan Seleksi KIP-K</h1>
            <p class="text-secondary mb-0">Pantau user, jawaban tes, kuota, dan hasil kelulusan terbaru.</p>
        </div>
        <span class="badge badge-soft">{{ $summary['processed'] }} hasil diproses</span>
    </div>

    <div class="row g-3">
        @foreach($stats as $label => $value)
            @php
                $accent = match ($label) {
                    'Total User' => 'var(--blue)',
                    'Terverifikasi' => 'var(--teal)',
                    'Sudah Tes' => 'var(--amber)',
                    'Tidak Lulus' => 'var(--rose)',
                    default => 'var(--blue)',
                };
            @endphp
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card interactive-card" style="--accent: {{ $accent }};">
                    <small>{{ $label }}</small>
                    <div class="fs-3 fw-bold mt-1">{{ $value }}</div>
                    @if($label === 'Sudah Tes')
                        <div class="text-secondary small mt-2">{{ $summary['answered_percent'] }}% dari total user.</div>
                    @elseif($label === 'Kuota Lulus')
                        <div class="text-secondary small mt-2">Sisa kuota: {{ $summary['quota_remaining'] }}.</div>
                    @elseif($label === 'Soal Aktif')
                        <div class="text-secondary small mt-2">Soal yang tampil di tes user.</div>
                    @elseif($label === 'Lulus')
                        <div class="text-secondary small mt-2">Status hasil seleksi hijau.</div>
                    @elseif($label === 'Tidak Lulus')
                        <div class="text-secondary small mt-2">Status hasil seleksi merah.</div>
                    @else
                        <div class="text-secondary small mt-2">Data pendaftar saat ini.</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="border rounded bg-white p-3 mt-4">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
            <div>
                <h2 class="h6 mb-1">Grafik Data Seleksi</h2>
                <div class="text-secondary small">Visual ringkas berdasarkan data terbaru di sistem.</div>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.quota') }}">Atur Kuota</a>
        </div>
        <canvas id="statsChart" height="90"></canvas>
    </div>
</div>

@include('partials.kipk-dashboard')
@push('scripts')
<script>
window.chartData = @json($stats);
</script>
@endpush
@endsection
