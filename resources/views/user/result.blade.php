@extends('layouts.user')

@section('user-content')
<div class="content-card p-4" data-reveal>
    <div class="hero-kicker">Hasil Seleksi</div>
    <h1 class="h4 mb-3">Pengumuman KIP-K</h1>

    <div class="text-center py-5">
        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-white border overflow-hidden" style="width: 72px; height: 72px;">
            <img src="{{ asset('images/permakip-logo.png') }}" alt="Logo PERMAKIP UMT" class="w-100 h-100 object-fit-contain p-2">
        </div>
        <h2 class="h5 mb-2">Pantau pengumuman resmi melalui Instagram PERMAKIP UMT</h2>
        <p class="text-secondary mb-4">Informasi hasil seleksi untuk pendaftar diarahkan melalui akun resmi Universitas Muhammadiyah Tangerang.</p>
        <a class="btn btn-primary" href="https://www.instagram.com/permakip_umt/" target="_blank" rel="noopener">
            Buka @permakip_umt
        </a>
    </div>
</div>
@endsection
