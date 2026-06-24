@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
        <div>
            <div class="hero-kicker">Pengaturan Seleksi</div>
            <h1 class="h4 mb-1">Kuota Lulus</h1>
            <p class="text-secondary mb-0">Atur jumlah pendaftar yang dinyatakan lulus berdasarkan ranking SAW.</p>
        </div>
        <span class="badge badge-soft">{{ $verifiedCount }} pendaftar terverifikasi</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <form method="post" action="{{ route('admin.quota.update') }}" class="border rounded bg-white p-3">
                @csrf
                <label class="form-label" for="selectionQuota">Jumlah Kuota Lulus</label>
                <div class="input-group mb-3">
                    <input
                        id="selectionQuota"
                        name="selection_quota"
                        type="number"
                        min="1"
                        max="10000"
                        class="form-control"
                        value="{{ old('selection_quota', $setting->selection_quota) }}"
                        required
                    >
                    <span class="input-group-text">orang</span>
                </div>
                <button class="btn btn-primary w-100">Simpan Kuota</button>
            </form>
        </div>

        <div class="col-lg-7">
            <div class="border rounded bg-white p-3 h-100">
                <h2 class="h6 mb-2">Cara Kerja Kuota</h2>
                <div class="timeline">
                    <div class="timeline-item done">
                        <span class="timeline-dot">1</span>
                        <div>
                            <strong>Sistem menghitung nilai akhir SAW</strong>
                            <div class="text-secondary small">Semua pendaftar terverifikasi diurutkan dari nilai tertinggi.</div>
                        </div>
                    </div>
                    <div class="timeline-item done">
                        <span class="timeline-dot">2</span>
                        <div>
                            <strong>Ranking masuk kuota menjadi Lulus</strong>
                            <div class="text-secondary small">Jika kuota 10, ranking 1 sampai 10 berstatus Lulus.</div>
                        </div>
                    </div>
                    <div class="timeline-item done">
                        <span class="timeline-dot">3</span>
                        <div>
                            <strong>Ranking di luar kuota menjadi Tidak Lulus</strong>
                            <div class="text-secondary small">Setelah kuota disimpan, hasil SAW langsung dihitung ulang.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
