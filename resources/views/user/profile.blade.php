@extends('layouts.user')

@section('user-content')
<div class="content-card p-4" data-reveal>
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
        <div>
            <div class="hero-kicker">Data Pendaftar</div>
            <h1 class="h4 mb-1">Profil Pendaftar</h1>
            <p class="text-secondary mb-0">Data berikut sesuai dengan isian registrasi pendaftar.</p>
        </div>
        <div class="text-end" style="min-width: 180px;">
            <small class="text-secondary d-block mb-1">Kelengkapan form</small>
            <div class="form-progress" aria-hidden="true"><span data-form-progress></span></div>
        </div>
    </div>

    <form method="post" action="{{ route('user.profile') }}" data-smart-form>
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Akun KIP</label>
                <input name="kip_account_number" class="form-control" value="{{ old('kip_account_number', $profile?->kip_account_number) }}" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Asal Sekolah</label>
                <input name="school_origin" class="form-control" value="{{ old('school_origin', $profile?->school_origin) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun Masuk</label>
                <select name="entry_year" class="form-select" required>
                    <option value="">Pilih tahun masuk</option>
                    @for($year = now()->year + 1; $year >= now()->year - 10; $year--)
                        <option value="{{ $year }}" @selected((string) old('entry_year', $profile?->entry_year) === (string) $year)>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">NISN</label>
                <input name="nisn" class="form-control" value="{{ old('nisn', $profile?->nisn) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">NPSN</label>
                <input name="npsn" class="form-control" value="{{ old('npsn', $profile?->npsn) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nomor WA</label>
                <input name="phone" class="form-control" value="{{ old('phone', $profile?->phone) }}" required>
            </div>
        </div>
        <button class="btn btn-primary mt-3">Simpan Profil</button>
    </form>
</div>
@endsection
