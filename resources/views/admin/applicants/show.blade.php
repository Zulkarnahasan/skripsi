@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <h1 class="h4">{{ $applicant->user->name }}</h1>
    <p class="text-secondary">{{ $applicant->registration_number }} - {{ $applicant->status }}</p>
    <dl class="row"><dt class="col-sm-3">Nomor Akun KIP</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->kip_account_number }}</dd><dt class="col-sm-3">Asal Sekolah</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->school_origin }}</dd><dt class="col-sm-3">Tahun Masuk</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->entry_year ?? '-' }}</dd><dt class="col-sm-3">NISN</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->nisn }}</dd><dt class="col-sm-3">NPSN</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->npsn }}</dd><dt class="col-sm-3">Nomor WA</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->phone }}</dd><dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $applicant->studentProfile?->address }}</dd></dl>
    <h2 class="h5">Dokumen</h2>
    <ul>@foreach($applicant->studentProfile?->documents ?? [] as $doc)<li>{{ $doc->document_type }} - <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank">lihat</a></li>@endforeach</ul>
</div>
@endsection
