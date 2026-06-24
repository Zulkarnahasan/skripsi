@extends('layouts.user')

@section('user-content')
@php
    $resultLabels = ['accepted' => 'Lulus', 'rejected' => 'Tidak Lulus'];
    $resultStatus = $resultLabels[$alternative?->sawResult?->status] ?? '-';
@endphp

<div class="content-card p-4" data-reveal>
    <div class="hero-kicker">Hasil Seleksi</div>
    <h1 class="h4 mb-3">Pengumuman KIP-K</h1>

    @if($alternative?->sawResult?->announced_at)
        <div class="alert {{ $alternative->sawResult->status === 'accepted' ? 'alert-success' : 'alert-danger' }} mb-4">
            @if($alternative->sawResult->status === 'accepted')
                <h2 class="h5 mb-2">Selamat, Anda dinyatakan Lulus</h2>
                <p class="mb-0">Kepada peserta ujian penerima KIP-K yang telah lulus. Semoga kesempatan ini menjadi awal perjalanan menuju masa depan yang sukses dan penuh prestasi. Terus semangat dan jangan berhenti berjuang!</p>
            @else
                <h2 class="h5 mb-2">Mohon maaf, Anda dinyatakan Tidak Lulus</h2>
                <p class="mb-0">Terima kasih telah mengikuti proses seleksi KIP-K Universitas Muhammadiyah Tangerang. Tetap semangat dan terus berjuang untuk kesempatan berikutnya.</p>
            @endif
        </div>

    @else
        <div class="text-center py-5">
            <div class="mx-auto mb-3 timeline-dot" style="width: 54px; height: 54px;">i</div>
            <h2 class="h5">Hasil belum diumumkan</h2>
            <p class="text-secondary mb-0">Silakan pantau halaman ini setelah admin menyelesaikan proses seleksi.</p>
        </div>
    @endif
</div>
@endsection
