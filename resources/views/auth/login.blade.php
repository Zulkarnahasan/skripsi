@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center auth-page">
    <div class="col-lg-10">
        <div class="content-card auth-shell">
            <div class="auth-visual">
                <div class="auth-logo-group">
                    <a href="https://umt.ac.id/" target="_blank" rel="noopener" aria-label="Website Universitas Muhammadiyah Tangerang">
                        <img class="auth-logo" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Logo-umt.png" alt="Logo Universitas Muhammadiyah Tangerang">
                    </a>
                    <img class="auth-logo" src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP Unit Indonesia">
                </div>
                <div class="auth-visual-title">
                    <p class="mb-0">Portal KIP-K untuk pendaftar Universitas Muhammadiyah Tangerang.</p>
                </div>
            </div>
            <div class="auth-form-panel">
                <div>
                    <h2 class="h4 auth-form-title">Ujian Penerimaan Mahasiswa KIP-K Universitas Muhammadiyah Tangerang</h2>
                    <form method="post" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nomor Akun KIP</label>
                            <input name="login" class="form-control" value="{{ old('login') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input name="password" type="password" class="form-control" data-password required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password aria-label="Lihat password">Lihat</button>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100">Masuk</button>
                        @if($setting->registration_open)
                            <a class="btn btn-outline-primary w-100 mt-2" href="{{ route('register') }}">Buat akun</a>
                        @else
                            <button class="btn btn-outline-secondary w-100 mt-2" type="button" disabled>Pendaftaran ditutup</button>
                            <div class="small text-secondary mt-2 text-center">Pembuatan akun baru sedang dinonaktifkan oleh admin.</div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="whatsapp-help" href="https://wa.me/6281292580199" target="_blank" rel="noopener" aria-label="Bantuan WhatsApp">
    <svg width="28" height="28" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
        <path d="M16.03 3.2A12.74 12.74 0 0 0 5.1 22.5L3.5 28.8l6.43-1.54A12.73 12.73 0 1 0 16.03 3.2Zm0 2.28a10.45 10.45 0 1 1-5.33 19.44l-.39-.23-3.63.87.9-3.5-.25-.4A10.45 10.45 0 0 1 16.03 5.48Zm-4.58 5.12c-.23 0-.6.08-.92.43-.32.35-1.2 1.18-1.2 2.86 0 1.68 1.23 3.31 1.4 3.54.17.23 2.38 3.81 5.88 5.19 2.91 1.15 3.5.92 4.13.86.63-.06 2.03-.83 2.32-1.63.29-.8.29-1.49.2-1.63-.09-.14-.32-.23-.66-.4-.34-.17-2.03-1-2.34-1.12-.31-.11-.54-.17-.77.17-.23.34-.89 1.12-1.09 1.35-.2.23-.4.26-.74.09-.34-.17-1.43-.53-2.73-1.69-1.01-.9-1.69-2.01-1.89-2.35-.2-.34-.02-.52.15-.69.15-.15.34-.4.51-.6.17-.2.23-.34.34-.57.11-.23.06-.43-.03-.6-.09-.17-.77-1.86-1.06-2.55-.28-.67-.56-.58-.77-.59h-.69Z"/>
    </svg>
    <span>Bantuan</span>
</a>
@endsection
