@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center auth-page">
    <div class="col-lg-10">
        <div class="content-card auth-shell">
            <div class="auth-visual">
                <div class="auth-logo-group">
                    <img class="auth-logo" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Logo-umt.png" alt="Logo Universitas Muhammadiyah Tangerang">
                    <img class="auth-logo" src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP Unit Indonesia">
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
                                <label class="form-label">Pilihan Prodi 1</label>
                                <select name="study_program" class="form-select" required>
                                    <option value="">Pilih prodi 1</option>
                                    @foreach($programs as $key => $program)
                                        <option value="{{ $key }}" @selected(old('study_program') === $key)>
                                            {{ $program['level'] }} {{ $program['name'] }} - Akreditasi {{ $program['accreditation'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilihan Prodi 2</label>
                                <select name="study_program_2" class="form-select" required>
                                    <option value="">Pilih prodi 2</option>
                                    @foreach($programs as $key => $program)
                                        <option value="{{ $key }}" @selected(old('study_program_2') === $key)>
                                            {{ $program['level'] }} {{ $program['name'] }} - Akreditasi {{ $program['accreditation'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3"><label class="form-label">NISN</label><input name="nisn" class="form-control" value="{{ old('nisn') }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" required></div>
                            <div class="mb-3"><label class="form-label">NPSN</label><input name="npsn" class="form-control" value="{{ old('npsn') }}" inputmode="numeric" pattern="[0-9]{8}" maxlength="8" required></div>
                            <div class="mb-3"><label class="form-label">Nomor WA</label><input name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input name="password" type="password" class="form-control" data-password required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password aria-label="Lihat password">Lihat</button>
                                </div>
                                <div class="form-text">Minimal 8 karakter. Password ini digunakan untuk login bersama Nomor Akun KIP.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input name="password_confirmation" type="password" class="form-control" data-password required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password aria-label="Lihat konfirmasi password">Lihat</button>
                                </div>
                            </div>
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
