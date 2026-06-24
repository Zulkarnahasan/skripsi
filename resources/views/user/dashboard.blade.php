@extends('layouts.user')

@section('user-content')
@php
    $profileStatus = $user->studentProfile?->status ?? 'Belum diisi';
    $resultLabels = ['accepted' => 'Lulus', 'rejected' => 'Tidak Lulus'];
    $resultStatus = $resultLabels[$user->alternative?->sawResult?->status] ?? 'Belum ada';
    $documentsCount = $user->documents->count();
@endphp

<div class="content-card user-hero mb-3" data-reveal>
    <div>
        <div class="hero-kicker">Dashboard Pendaftar</div>
        <h1 class="h3 mb-2">Halo, {{ $user->name }}</h1>
        <p class="text-secondary mb-0">Nomor registrasi: <strong>{{ $user->alternative?->registration_number ?? '-' }}</strong></p>
    </div>
    <div class="quick-actions">
        <a class="soft-button" href="{{ route('user.profile') }}">Lengkapi Profil</a>
        <a class="soft-button" href="{{ route('user.test') }}">Isi Tes</a>
        <a class="soft-button" href="{{ route('user.documents') }}">Upload Dokumen</a>
        <a class="soft-button" href="{{ route('user.status') }}">Cek Status</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4" data-reveal>
        <div class="stat-card interactive-card" style="--accent: var(--teal);">
            <small>Status Profil</small>
            <div class="fs-5 fw-bold mt-1">{{ $profileStatus }}</div>
            <div class="text-secondary small mt-2">Data utama pendaftar.</div>
        </div>
    </div>
    <div class="col-md-4" data-reveal>
        <div class="stat-card interactive-card" style="--accent: var(--blue);">
            <small>Dokumen</small>
            <div class="fs-5 fw-bold mt-1">{{ $documentsCount }} file</div>
            <div class="text-secondary small mt-2">Berkas yang sudah terunggah.</div>
        </div>
    </div>
    <div class="col-md-4" data-reveal>
        <div class="stat-card interactive-card" style="--accent: var(--amber);">
            <small>Hasil</small>
            <div class="fs-5 fw-bold mt-1">{{ $resultStatus }}</div>
            <div class="text-secondary small mt-2">Status seleksi terbaru.</div>
        </div>
    </div>
</div>

@php
    $umtFaculties = [
        ['name' => 'Fakultas Ekonomi dan Bisnis', 'programs' => 'Manajemen, Akuntansi, Bisnis Digital'],
        ['name' => 'Fakultas Teknik', 'programs' => 'Teknik Informatika, Teknik Mesin, Teknik Industri, Teknik Sipil, Teknik Elektro'],
        ['name' => 'Fakultas Keguruan dan Ilmu Pendidikan', 'programs' => 'PG PAUD, PGSD, Bahasa dan Sastra Indonesia'],
        ['name' => 'Fakultas Ilmu Sosial dan Ilmu Politik', 'programs' => 'Ilmu Komunikasi, Ilmu Pemerintahan'],
        ['name' => 'Fakultas Agama Islam', 'programs' => 'Pendidikan Agama Islam, Perbankan Syariah, Pendidikan Bahasa Arab'],
        ['name' => 'Fakultas Ilmu Kesehatan', 'programs' => 'Program kesehatan UMT'],
        ['name' => 'Fakultas Hukum', 'programs' => 'Ilmu Hukum'],
        ['name' => 'Fakultas Pariwisata dan Industri Kreatif', 'programs' => 'Program pariwisata dan industri kreatif'],
    ];

    $umtFacilities = [
        'Laboratorium Komputer',
        'Laboratorium Pengadilan Semu',
        'Laboratorium Keperawatan dan Kebidanan',
        'Laboratorium Multimedia',
        'Laboratorium Micro Teaching',
        'Laboratorium Bahasa',
        'Perpustakaan',
        'Ruang Kuliah Full AC',
        'Sarana Ibadah',
    ];

    $umtDocumentation = [
        [
            'title' => 'Gedung kampus Universitas Muhammadiyah Tangerang',
            'image' => asset('images/umt-campus.jpg'),
            'url' => 'https://umt.ac.id/',
            'source' => 'Website UMT',
        ],
        [
            'title' => 'Layanan informasi PMB Universitas Muhammadiyah Tangerang',
            'image' => 'https://pmblapemba.umt.ac.id/asset/images/callnara.jpg',
            'url' => 'https://pmblapemba.umt.ac.id/',
            'source' => 'PMB UMT',
        ],
        [
            'title' => 'Laporan kegiatan mahasiswa KKN UMT',
            'image' => 'https://kkn.umt.ac.id/storage/webp/laporan/kelompok/66d30db9a41b5-1725107641.webp',
            'url' => 'https://kkn.umt.ac.id/kegiatan/155717',
            'source' => 'KKN UMT',
        ],
        [
            'title' => 'Seminar literasi keuangan bersama mahasiswa UMT',
            'image' => 'https://kkn.umt.ac.id/storage/media/66d304a22cea4-1725105314.jpeg',
            'url' => 'https://kkn.umt.ac.id/kegiatan/155717',
            'source' => 'KKN UMT',
        ],
        [
            'title' => 'Kegiatan mengajar PAUD oleh mahasiswa UMT',
            'image' => 'https://kkn.umt.ac.id/storage/media/66d308984c8df-1725106328.jpeg',
            'url' => 'https://kkn.umt.ac.id/kegiatan/155717',
            'source' => 'KKN UMT',
        ],
        [
            'title' => 'Sosialisasi anti bullying bersama mahasiswa UMT',
            'image' => 'https://kkn.umt.ac.id/storage/media/66d309722ca2f-1725106546.jpeg',
            'url' => 'https://kkn.umt.ac.id/kegiatan/155717',
            'source' => 'KKN UMT',
        ],
    ];
@endphp

<div class="content-card p-4 mb-4" data-reveal>
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <h2 class="h5 mb-1">Alur Pendaftaran</h2>
            <p class="text-secondary mb-0">Pantau tahapan utama dari profil sampai pengumuman.</p>
        </div>
        <span class="badge badge-soft">Interaktif</span>
    </div>
    <div class="timeline">
        <div class="timeline-item {{ $user->studentProfile ? 'done' : '' }}">
            <span class="timeline-dot">1</span>
            <div>
                <strong>Profil pendaftar</strong>
                <div class="text-secondary small">{{ $user->studentProfile ? 'Profil sudah tersimpan.' : 'Lengkapi data profil terlebih dahulu.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $documentsCount > 0 ? 'done' : '' }}">
            <span class="timeline-dot">2</span>
            <div>
                <strong>Dokumen pendukung</strong>
                <div class="text-secondary small">{{ $documentsCount > 0 ? $documentsCount.' dokumen sudah diunggah.' : 'Unggah dokumen yang diminta panitia.' }}</div>
            </div>
        </div>
        <div class="timeline-item {{ $user->alternative?->sawResult?->announced_at ? 'done' : '' }}">
            <span class="timeline-dot">3</span>
            <div>
                <strong>Pengumuman hasil</strong>
                <div class="text-secondary small">{{ $user->alternative?->sawResult?->announced_at ? 'Hasil sudah diumumkan.' : 'Menunggu proses verifikasi dan pengumuman admin.' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="content-card p-4 mb-4" data-reveal>
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
        <div>
            <h2 class="h5 mb-1">Informasi Universitas Muhammadiyah Tangerang</h2>
            <p class="text-secondary mb-0">Ringkasan fakultas, fasilitas, dan kontak PMB dari laman resmi UMT.</p>
        </div>
        <a class="btn btn-sm btn-outline-primary" href="https://umt.ac.id/" target="_blank" rel="noopener">Website UMT</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="border rounded p-3 h-100 bg-white">
                <div class="d-flex justify-content-between gap-3 align-items-center mb-2">
                    <h3 class="h6 mb-0">Fakultas dan Program Studi</h3>
                    <span class="badge badge-soft">{{ count($umtFaculties) }} fakultas</span>
                </div>
                <div class="row g-2">
                    @foreach($umtFaculties as $faculty)
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <strong>{{ $faculty['name'] }}</strong>
                                <div class="text-secondary small mt-1">{{ $faculty['programs'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="border rounded p-3 bg-white mb-3">
                <h3 class="h6 mb-2">Fasilitas Penunjang</h3>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($umtFacilities as $facility)
                        <span class="badge text-bg-light border">{{ $facility }}</span>
                    @endforeach
                </div>
            </div>
            <div class="border rounded p-3 bg-white">
                <h3 class="h6 mb-2">Kontak PMB UMT</h3>
                <dl class="row mb-0 small">
                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">Jl. P. Kemerdekaan No.33, Cikokol, Babakan, Kota Tangerang, Banten 15118</dd>
                    <dt class="col-sm-4">Call Center</dt>
                    <dd class="col-sm-8">0821-1316-2009</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">pmb@umt.ac.id</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="border rounded p-3 bg-white mt-3">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
            <div>
                <h3 class="h6 mb-1">Dokumentasi Universitas Muhammadiyah Tangerang</h3>
                <small class="text-secondary">Campuran dokumentasi kampus, PMB, dan kegiatan mahasiswa UMT.</small>
            </div>
            <small class="text-secondary">Sumber: UMT, PMB UMT, KKN UMT</small>
        </div>
        <div class="documentation-grid">
            @foreach($umtDocumentation as $item)
                <figure class="documentation-card mb-0">
                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">
                    </a>
                    <figcaption>{{ $item['title'] }}</figcaption>
                    <small class="text-secondary d-block mt-1">{{ $item['source'] }}</small>
                </figure>
            @endforeach
        </div>
    </div>
    <div class="small text-secondary mt-3">
        Sumber:
        <a href="https://pmblapemba.umt.ac.id/index.php/home/panduan" target="_blank" rel="noopener">Fasilitas PMB UMT</a>,
        <a href="https://pmblapemba.umt.ac.id/index.php/home/panitia" target="_blank" rel="noopener">Kontak Panitia PMB UMT</a>,
        <a href="https://pps.umt.ac.id/" target="_blank" rel="noopener">Daftar fakultas UMT</a>,
        <a href="https://umt.ac.id/" target="_blank" rel="noopener">Website UMT</a>,
        <a href="https://pmblapemba.umt.ac.id/" target="_blank" rel="noopener">Dokumentasi PMB UMT</a>,
        <a href="https://kkn.umt.ac.id/kegiatan/155717" target="_blank" rel="noopener">Dokumentasi KKN UMT</a>.
    </div>
</div>

@include('partials.kipk-dashboard')
@endsection
