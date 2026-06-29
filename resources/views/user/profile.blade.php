@extends('layouts.user')

@section('user-content')
@php
    $selectedProgram = old('study_program');
    $selectedProgram2 = old('study_program_2');

    if (! $selectedProgram && $profile?->study_program) {
        $selectedProgram = collect($programs)->search(fn ($program) => $program['name'] === $profile->study_program || "{$program['level']} {$program['name']}" === $profile->study_program);
    }

    if (! $selectedProgram2 && $profile?->study_program_2) {
        $selectedProgram2 = collect($programs)->search(fn ($program) => $program['name'] === $profile->study_program_2 || "{$program['level']} {$program['name']}" === $profile->study_program_2);
    }

    $isFilled = fn ($value): bool => filled($value);
    $fieldCheck = fn ($filled) => $filled
        ? new \Illuminate\Support\HtmlString('<span class="ms-1 text-success" title="Sudah terisi" aria-label="Sudah terisi"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="vertical-align:-2px;"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>')
        : new \Illuminate\Support\HtmlString('<span class="ms-1 text-danger" title="Belum terisi" aria-label="Belum terisi"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="vertical-align:-2px;"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>');
@endphp

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

    <form method="post" action="{{ route('user.profile') }}" enctype="multipart/form-data" data-smart-form>
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3 rounded-3 border p-3">
                    <div class="rounded-circle overflow-hidden bg-light border d-flex align-items-center justify-content-center" style="width: 88px; height: 88px;">
                        @if($profile?->profile_photo_path)
                            <img src="{{ asset('storage/'.$profile->profile_photo_path) }}" alt="Foto profil {{ $user->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="fw-bold text-secondary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label">Foto Profil {!! $fieldCheck($isFilled($profile?->profile_photo_path)) !!}</label>
                        <input type="file" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <div class="form-text">Format JPG atau PNG, maksimal 1 MB.</div>
                        <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small">
                            Gunakan foto formal dengan wajah terlihat jelas, menghadap kamera, dan tidak tertutup masker, topi, atau kacamata gelap.
                        </div>
                        @error('profile_photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap {!! $fieldCheck($isFilled(old('name', $user->name))) !!}</label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Akun KIP {!! $fieldCheck($isFilled(old('kip_account_number', $profile?->kip_account_number))) !!}</label>
                <input name="kip_account_number" class="form-control" value="{{ old('kip_account_number', $profile?->kip_account_number) }}" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Asal Sekolah {!! $fieldCheck($isFilled(old('school_origin', $profile?->school_origin))) !!}</label>
                <input name="school_origin" class="form-control" value="{{ old('school_origin', $profile?->school_origin) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Pilihan Prodi 1 {!! $fieldCheck($isFilled($selectedProgram)) !!}</label>
                <select name="study_program" class="form-select" required>
                    <option value="">Pilih prodi 1</option>
                    @foreach($programs as $key => $program)
                        <option value="{{ $key }}" @selected($selectedProgram === $key)>
                            {{ $program['level'] }} {{ $program['name'] }} - Akreditasi {{ $program['accreditation'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Pilihan Prodi 2 {!! $fieldCheck($isFilled($selectedProgram2)) !!}</label>
                <select name="study_program_2" class="form-select" required>
                    <option value="">Pilih prodi 2</option>
                    @foreach($programs as $key => $program)
                        <option value="{{ $key }}" @selected($selectedProgram2 === $key)>
                            {{ $program['level'] }} {{ $program['name'] }} - Akreditasi {{ $program['accreditation'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun Masuk {!! $fieldCheck($isFilled(old('entry_year', $profile?->entry_year))) !!}</label>
                <select name="entry_year" class="form-select" required>
                    <option value="">Pilih tahun masuk</option>
                    @for($year = now()->year + 1; $year >= now()->year - 10; $year--)
                        <option value="{{ $year }}" @selected((string) old('entry_year', $profile?->entry_year) === (string) $year)>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun Lulus Sekolah {!! $fieldCheck($isFilled(old('graduation_year', $profile?->graduation_year))) !!}</label>
                <select name="graduation_year" class="form-select" required>
                    <option value="">Pilih tahun lulus</option>
                    @for($year = now()->year + 1; $year >= now()->year - 10; $year--)
                        <option value="{{ $year }}" @selected((string) old('graduation_year', $profile?->graduation_year) === (string) $year)>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">NISN {!! $fieldCheck($isFilled(old('nisn', $profile?->nisn))) !!}</label>
                <input name="nisn" class="form-control" value="{{ old('nisn', $profile?->nisn) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">NPSN {!! $fieldCheck($isFilled(old('npsn', $profile?->npsn))) !!}</label>
                <input name="npsn" class="form-control" value="{{ old('npsn', $profile?->npsn) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nomor WA {!! $fieldCheck($isFilled(old('phone', $profile?->phone))) !!}</label>
                <input name="phone" class="form-control" value="{{ old('phone', $profile?->phone) }}" required>
            </div>
        </div>
        <button class="btn btn-primary mt-3">Simpan Profil</button>
    </form>
</div>
@endsection
