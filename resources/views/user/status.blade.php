@extends('layouts.user')

@section('user-content')
@php
    $profileComplete = $user->hasCompleteProfile();
    $requiredDocumentCount = count(\App\Models\Document::requiredTypeKeys());
    $uploadedRequiredDocumentCount = $user->documents
        ->whereIn('document_type', \App\Models\Document::requiredTypeKeys())
        ->pluck('document_type')
        ->unique()
        ->count();
    $documentsComplete = $profileComplete && $uploadedRequiredDocumentCount >= $requiredDocumentCount;
    $verificationComplete = $documentsComplete && $user->alternative?->status === 'verified';
@endphp

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
                <div class="fs-5 fw-bold mt-1">{{ $uploadedRequiredDocumentCount }}/{{ $requiredDocumentCount }} wajib</div>
            </div>
        </div>
    </div>

    <div class="timeline mt-4">
        <div class="timeline-item {{ $profileComplete ? 'done' : '' }}">
            <span class="timeline-dot">1</span>
            <div>
                <strong>Profil dikirim</strong>
                <div class="text-secondary small">{{ $profileComplete ? 'Data profil sudah lengkap dan diterima sistem.' : 'Lengkapi semua bagian profil yang masih bertanda X merah.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $documentsComplete ? 'done' : '' }}">
            <span class="timeline-dot">2</span>
            <div>
                <strong>Dokumen masuk</strong>
                <div class="text-secondary small">{{ $profileComplete ? ($documentsComplete ? 'Semua dokumen wajib siap diverifikasi admin.' : $uploadedRequiredDocumentCount.'/'.$requiredDocumentCount.' dokumen wajib sudah diunggah.') : 'Tahap dokumen terbuka setelah profil lengkap.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $verificationComplete ? 'done' : '' }}">
            <span class="timeline-dot">3</span>
            <div>
                <strong>Verifikasi admin</strong>
                <div class="text-secondary small">Status saat ini: {{ $user->alternative?->status ?? 'Belum dibuat' }}.</div>
            </div>
        </div>
    </div>
</div>
@endsection
