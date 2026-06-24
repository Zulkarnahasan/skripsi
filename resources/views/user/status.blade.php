@extends('layouts.user')

@section('user-content')
<div class="content-card p-4" data-reveal>
    <div class="hero-kicker">Status Verifikasi</div>
    <h1 class="h4 mb-3">Pantauan Pendaftaran</h1>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="stat-card interactive-card" style="--accent: var(--teal);">
                <small>Profil</small>
                <div class="fs-5 fw-bold mt-1">{{ $user->studentProfile?->status ?? 'Belum diisi' }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card interactive-card" style="--accent: var(--blue);">
                <small>Pendaftaran</small>
                <div class="fs-5 fw-bold mt-1">{{ $user->alternative?->status ?? 'Belum dibuat' }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card interactive-card" style="--accent: var(--amber);">
                <small>Dokumen Terunggah</small>
                <div class="fs-5 fw-bold mt-1">{{ $user->documents->count() }} file</div>
            </div>
        </div>
    </div>

    <div class="timeline mt-4">
        <div class="timeline-item {{ $user->studentProfile ? 'done' : '' }}">
            <span class="timeline-dot">1</span>
            <div>
                <strong>Profil dikirim</strong>
                <div class="text-secondary small">{{ $user->studentProfile ? 'Data profil sudah diterima sistem.' : 'Profil belum dikirim.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $user->documents->count() > 0 ? 'done' : '' }}">
            <span class="timeline-dot">2</span>
            <div>
                <strong>Dokumen masuk</strong>
                <div class="text-secondary small">{{ $user->documents->count() > 0 ? 'Dokumen siap diverifikasi admin.' : 'Belum ada dokumen pendukung.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $user->alternative?->status === 'verified' ? 'done' : '' }}">
            <span class="timeline-dot">3</span>
            <div>
                <strong>Verifikasi admin</strong>
                <div class="text-secondary small">Status saat ini: {{ $user->alternative?->status ?? 'Belum dibuat' }}.</div>
            </div>
        </div>
    </div>
</div>
@endsection
