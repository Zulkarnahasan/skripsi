@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center auth-page">
    <div class="col-lg-10">
        <div class="content-card auth-shell">
            <div class="auth-visual">
                <div class="auth-logo-group">
                    <img class="auth-logo" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Logo-umt.png" alt="Logo Universitas Muhammadiyah Tangerang">
                    <img class="auth-logo" src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP Unit Indonesia">
                    <img class="auth-logo" src="https://commons.wikimedia.org/wiki/Special:FilePath/Logo%20Kemendikbud.svg" alt="Logo Kemendikbud">
                </div>
                <div class="auth-visual-title">
                    <p class="mb-0">Daftar pendaftar KIP-K dengan data sekolah dan akun KIP yang valid.</p>
                </div>
            </div>
            <div class="auth-form-panel">
                <div>
                    <h2 class="h4 auth-form-title">Ujian Penerimaan Mahasiswa KIP-K Universitas Muhammadiyah Tangerang</h2>
                    @if($setting->registration_open)
                        <form method="post" action="/register">@csrf
                            <div class="mb-3"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
                            <div class="mb-3"><label class="form-label">Nomor Akun KIP</label><input name="kip_account_number" class="form-control" value="{{ old('kip_account_number') }}" required></div>
                            <div class="mb-3"><label class="form-label">Asal Sekolah</label><input name="school_origin" class="form-control" value="{{ old('school_origin') }}" required></div>
                            <div class="mb-3">
                                <label class="form-label">Tahun Masuk</label>
                                <select name="entry_year" class="form-select" required>
                                    <option value="">Pilih tahun masuk</option>
                                    @for($year = now()->year + 1; $year >= now()->year - 10; $year--)
                                        <option value="{{ $year }}" @selected((string) old('entry_year') === (string) $year)>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3"><label class="form-label">NISN</label><input name="nisn" class="form-control" value="{{ old('nisn') }}" required></div>
                            <div class="mb-3"><label class="form-label">NPSN</label><input name="npsn" class="form-control" value="{{ old('npsn') }}" required></div>
                            <div class="mb-3"><label class="form-label">Nomor WA</label><input name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                            <button class="btn btn-primary w-100">Daftar</button>
                            <a class="btn btn-outline-primary w-100 mt-2" href="{{ route('login') }}">Sudah ada akun</a>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Pendaftaran akun baru sedang ditutup oleh admin. Silakan gunakan akun yang sudah terdaftar untuk masuk.
                        </div>
                        <a class="btn btn-primary w-100" href="{{ route('login') }}">Sudah ada akun</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
