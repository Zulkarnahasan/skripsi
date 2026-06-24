@extends('layouts.app')

@section('content')
<section class="row align-items-center g-4 py-5">
    <div class="col-lg-7">
        <span class="badge text-bg-primary mb-3">Seleksi KIP-K berbasis SAW</span>
        <h1 class="display-5 fw-bold">Tes Ujian Penerimaan Mahasiswa KIP-K</h1>
        <p class="lead text-secondary">Pendaftar mengisi profil, unggah dokumen, mengikuti penilaian, lalu admin memverifikasi dan memproses ranking dengan metode Simple Additive Weighting.</p>
        <div class="d-flex gap-2">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login</a>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="p-4 border rounded-3 bg-light">
            <h2 class="h5">Alur Sistem</h2>
            <ol class="mb-0">
                <li>Pendaftar registrasi dan melengkapi profil.</li>
                <li>Admin verifikasi data dan dokumen.</li>
                <li>Admin input nilai kriteria dan proses SAW.</li>
                <li>Ranking diumumkan sesuai kuota penerima.</li>
            </ol>
        </div>
    </div>
</section>
@endsection
