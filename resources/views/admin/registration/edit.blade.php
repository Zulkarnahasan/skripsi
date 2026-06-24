@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <div class="hero-kicker">Keamanan Pendaftaran</div>
            <h1 class="h4 mb-1">Kontrol Pendaftaran Akun</h1>
            <p class="text-secondary mb-0">Tutup pendaftaran setelah proses ujian dimulai agar tidak ada akun baru yang masuk.</p>
        </div>
        <span class="badge {{ $setting->registration_open ? 'text-bg-success' : 'text-bg-danger' }}">
            {{ $setting->registration_open ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}
        </span>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <form method="post" action="{{ route('admin.registration.update') }}" class="border rounded bg-white p-3">
                @csrf
                <label class="form-label">Status Pendaftaran</label>
                <div class="d-grid gap-2">
                    <button name="registration_open" value="1" class="btn {{ $setting->registration_open ? 'btn-success' : 'btn-outline-success' }}">
                        Buka Pendaftaran
                    </button>
                    <button name="registration_open" value="0" class="btn {{ $setting->registration_open ? 'btn-outline-danger' : 'btn-danger' }}" onclick="return confirm('Tutup pendaftaran akun baru? User lama tetap bisa login.')">
                        Tutup Pendaftaran
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-7">
            <div class="border rounded bg-white p-3 h-100">
                <h2 class="h6 mb-2">Dampak Pengaturan</h2>
                <div class="timeline">
                    <div class="timeline-item done">
                        <span class="timeline-dot">1</span>
                        <div>
                            <strong>User lama tetap bisa login</strong>
                            <div class="text-secondary small">Menutup pendaftaran tidak memblokir akun yang sudah dibuat.</div>
                        </div>
                    </div>
                    <div class="timeline-item done">
                        <span class="timeline-dot">2</span>
                        <div>
                            <strong>Register akun baru dihentikan</strong>
                            <div class="text-secondary small">Tombol buat akun akan dinonaktifkan dan request register akan ditolak.</div>
                        </div>
                    </div>
                    <div class="timeline-item done">
                        <span class="timeline-dot">3</span>
                        <div>
                            <strong>Mengurangi percobaan sabotase</strong>
                            <div class="text-secondary small">Cocok digunakan saat tes sudah dimulai atau setelah tes selesai.</div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    Total user terdaftar saat ini: <strong>{{ $userCount }}</strong>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
