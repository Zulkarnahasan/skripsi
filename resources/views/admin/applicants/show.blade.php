@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <h1 class="h4">{{ $applicant->user->name }}</h1>
    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start mb-3">
        <p class="text-secondary mb-0">{{ $applicant->registration_number }} - {{ $applicant->status }}</p>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('quran-scores.index', ['q' => $applicant->user->name]) }}">Nilai Baca Quran</a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('interview-scores.index', ['q' => $applicant->user->name]) }}">Nilai Wawancara</a>
        </div>
    </div>
    <dl class="row"><dt class="col-sm-3">Nomor Akun KIP</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->kip_account_number }}</dd><dt class="col-sm-3">Asal Sekolah</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->school_origin }}</dd><dt class="col-sm-3">Pilihan Prodi 1</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->study_program ?? '-' }} @if($applicant->studentProfile?->study_program_accreditation)<span class="badge text-bg-success ms-1">Akreditasi {{ $applicant->studentProfile->study_program_accreditation }}</span>@endif</dd><dt class="col-sm-3">Pilihan Prodi 2</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->study_program_2 ?? '-' }} @if($applicant->studentProfile?->study_program_accreditation_2)<span class="badge text-bg-success ms-1">Akreditasi {{ $applicant->studentProfile->study_program_accreditation_2 }}</span>@endif</dd><dt class="col-sm-3">Tahun Masuk</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->entry_year ?? '-' }}</dd><dt class="col-sm-3">Tahun Lulus Sekolah</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->graduation_year ?? '-' }}</dd><dt class="col-sm-3">NISN</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->nisn }}</dd><dt class="col-sm-3">NPSN</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->npsn }}</dd><dt class="col-sm-3">Nomor WA</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->phone }}</dd><dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->address }}</dd></dl>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4 mb-3">
        <div>
            <h2 class="h5 mb-1">Dokumen</h2>
            <p class="text-secondary mb-0">Verifikasi setiap dokumen pendaftar satu per satu.</p>
        </div>
    </div>

    <div class="row g-3">
        @foreach($documentTypes as $type => $meta)
            @php
                $document = $documents->get($type);
                $statusLabels = [
                    'pending' => 'Menunggu verifikasi',
                    'verified' => 'Terverifikasi',
                    'rejected' => 'Ditolak',
                ];
            @endphp
            <div class="col-12">
                <div class="border rounded bg-white p-3">
                    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start mb-3">
                        <div>
                            <h3 class="h6 mb-1">{{ $meta['label'] }}</h3>
                            @if($meta['required'])
                                <span class="badge text-bg-danger">Wajib</span>
                            @else
                                <span class="badge text-bg-secondary">Jika Ada</span>
                            @endif
                        </div>
                        @if($document)
                            <span class="badge badge-soft">{{ $statusLabels[$document->status] ?? $document->status }}</span>
                        @else
                            <span class="badge badge-soft">Belum ada</span>
                        @endif
                    </div>

                    @if($document)
                        @php($documentUrl = asset('storage/'.$document->file_path))
                        <div class="border rounded bg-light overflow-hidden mb-3">
                            <iframe
                                src="{{ $documentUrl }}#toolbar=0&navpanes=0"
                                title="Preview {{ $meta['label'] }}"
                                style="width: 100%; height: 360px; border: 0;"
                                loading="lazy">
                            </iframe>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                            <a class="soft-button py-1" href="{{ $documentUrl }}" target="_blank" rel="noopener">Buka file</a>
                            <div class="d-flex flex-wrap gap-2">
                                <form method="post" action="{{ route('applicants.documents.status', [$applicant, $document]) }}">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="status" value="verified">
                                    <button class="btn btn-sm btn-success" @disabled($document->status === 'verified')>Verifikasi</button>
                                </form>
                                <form method="post" action="{{ route('applicants.documents.status', [$applicant, $document]) }}">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-danger" @disabled($document->status === 'rejected')>Tolak</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-secondary py-4 text-center border rounded bg-light">Menunggu upload user</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
