@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <h1 class="h4">Data Pendaftar</h1>
    <form class="row g-2 mb-3">
        <div class="col-md-5"><input name="q" class="form-control" value="{{ request('q') }}" placeholder="Search nama/NISN/NPSN/KIP/WA"></div>
        <div class="col-md-2"><select name="entry_year" class="form-select"><option value="">Semua tahun</option>@foreach($entryYears as $year)<option value="{{ $year }}" @selected((string) request('entry_year') === (string) $year)>{{ $year }}</option>@endforeach</select></div>
        <div class="col-md-3"><select name="status" class="form-select"><option value="">Semua status</option>@foreach(['submitted','verified','rejected'] as $s)<option @selected(request('status')===$s)>{{ $s }}</option>@endforeach</select></div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Cari</button>@if(request()->hasAny(['q', 'entry_year', 'status']))<a class="btn btn-outline-secondary" href="{{ route('applicants.index') }}">Reset</a>@endif</div>
    </form>
    <div class="table-responsive">
    <table class="table"><thead><tr><th>No</th><th>Nama</th><th>NISN</th><th>Asal Sekolah</th><th>Tahun Masuk</th><th>No. WA</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @foreach($applicants as $applicant)
            <tr><td>{{ $applicant->registration_number }}</td><td>{{ $applicant->user->name }}</td><td>{{ $applicant->studentProfile?->nisn }}</td><td>{{ $applicant->studentProfile?->school_origin }}</td><td>{{ $applicant->studentProfile?->entry_year ?? '-' }}</td><td>{{ $applicant->studentProfile?->phone }}</td><td><span id="status-{{ $applicant->id }}">{{ $applicant->status }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('applicants.show',$applicant) }}">Detail</a> <button class="btn btn-sm btn-success" data-status-url="{{ route('applicants.status',$applicant) }}" data-status-target="status-{{ $applicant->id }}" data-status-value="verified">Verifikasi</button> <button class="btn btn-sm btn-danger" data-status-url="{{ route('applicants.status',$applicant) }}" data-status-target="status-{{ $applicant->id }}" data-status-value="rejected">Tolak</button></td></tr>
        @endforeach
    </tbody></table>
    </div>
    {{ $applicants->links() }}
</div>
@endsection
