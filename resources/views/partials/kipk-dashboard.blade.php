@php
    $kipkProvinceData = [
        'Aceh' => 55517,
        'Sumatera Utara' => 84617,
        'Sumatera Barat' => 39118,
        'Riau' => 21827,
        'Jambi' => 13005,
        'Sumatera Selatan' => 27845,
        'Bengkulu' => 13052,
        'Lampung' => 26604,
        'Kep. Bangka Belitung' => 4208,
        'Kep. Riau' => 7062,
        'DKI Jakarta' => 32080,
        'Jawa Barat' => 126112,
        'Jawa Tengah' => 92506,
        'DI Yogyakarta' => 17256,
        'Jawa Timur' => 132265,
        'Banten' => 26533,
        'Bali' => 11316,
        'Kalimantan Barat' => 16355,
        'Kalimantan Tengah' => 6945,
        'Kalimantan Selatan' => 13043,
        'Kalimantan Timur' => 18845,
        'Kalimantan Utara' => 3225,
        'Sulawesi Utara' => 16953,
        'Sulawesi Tengah' => 18580,
        'Sulawesi Selatan' => 56700,
        'Sulawesi Tenggara' => 22793,
        'Gorontalo' => 9647,
        'Sulawesi Barat' => 17440,
        'Nusa Tenggara Barat' => 26735,
        'Nusa Tenggara Timur' => 43856,
        'Maluku' => 16036,
        'Maluku Utara' => 8374,
        'Papua' => 7001,
        'Papua Barat' => 5334,
        'Papua Barat Daya' => 4542,
        'Papua Tengah' => 2980,
        'Papua Pegunungan' => 2664,
        'Papua Selatan' => 4880,
    ];

    $kipkTopProvinces = collect($kipkProvinceData)->sortDesc()->take(10);
    $kipkRegions = [
        'Sumatera' => collect($kipkProvinceData)->only(['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kep. Bangka Belitung', 'Kep. Riau'])->sum(),
        'Jawa & Bali' => collect($kipkProvinceData)->only(['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali'])->sum(),
        'Kalimantan' => collect($kipkProvinceData)->only(['Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara'])->sum(),
        'Sulawesi' => collect($kipkProvinceData)->only(['Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat'])->sum(),
        'Nusa Tenggara & Maluku' => collect($kipkProvinceData)->only(['Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Maluku', 'Maluku Utara'])->sum(),
        'Papua' => collect($kipkProvinceData)->only(['Papua', 'Papua Barat', 'Papua Barat Daya', 'Papua Tengah', 'Papua Pegunungan', 'Papua Selatan'])->sum(),
    ];

    $kipkNews = [
        [
            'date' => '09 Mei 2026',
            'source' => 'Kemdiktisaintek',
            'title' => 'Dialog bersama mahasiswa penerima KIP Kuliah di Aceh',
            'summary' => 'Kemdiktisaintek menegaskan KIP Kuliah sebagai investasi strategis untuk akses pendidikan tinggi yang adil dan inklusif.',
            'url' => 'https://kemdiktisaintek.go.id/news/article/kemdiktisaintek-gelar-dialog-bersama-mahasiswa-penerima-kip-kuliah-di-aceh',
        ],
        [
            'date' => '09 Mei 2026',
            'source' => 'Kemdiktisaintek',
            'title' => 'Wamendiktisaintek beri motivasi penerima KIP Kuliah di USK',
            'summary' => 'Pemerintah menyampaikan alokasi anggaran KIP Kuliah 2026 sebesar Rp15,3 triliun dengan target sekitar satu juta mahasiswa.',
            'url' => 'https://kemdiktisaintek.go.id/news/article/wamendiktisaintek-beri-motivasi-penerima-kip-kuliah-di-usk',
        ],
        [
            'date' => '31 Maret 2026',
            'source' => 'Kemdiktisaintek',
            'title' => 'Hasil SNBP 2026 dan penguatan jaminan KIP Kuliah',
            'summary' => 'Sebanyak 64.471 pendaftar KIP Kuliah lulus SNBP 2026 dan 33.045 di antaranya berstatus eligible berdasarkan DTSEN desil 1-4.',
            'url' => 'https://kemdiktisaintek.go.id/news/article/hasil-snbp-2026-resmi-diumumkan-kemdiktisaintek-perkuat-jaminan-kip-kuliah',
        ],
        [
            'date' => '26 Maret 2026',
            'source' => 'Kemdiktisaintek',
            'title' => 'Sosialisasi KIP Kuliah jalur SNBT',
            'summary' => 'KIP Kuliah 2026 diperkuat dengan basis DTSEN untuk meningkatkan ketepatan sasaran, transparansi, dan akuntabilitas.',
            'url' => 'https://www.kemdiktisaintek.go.id/news/article/perluas-akses-pendidikan-tinggi-kemdiktisaintek-gelar-sosialisasi-kip-kuliah-jalur-snbt',
        ],
    ];

    $kipkDocumentation = [
        [
            'title' => 'Dialog bersama mahasiswa penerima KIP Kuliah di Aceh',
            'image' => 'https://kemdiktisaintek.go.id/_next/image?url=%2Fapi%2Ffile%2Fhumas-production%2Fnews%2F2026%2F5%2Fimages%2Fcfd35157-bcb3-4dcb-9a8e-852fc92b3d07%2FIMGL0153_1_.jpeg&w=3840&q=75',
            'url' => 'https://kemdiktisaintek.go.id/news/article/kemdiktisaintek-gelar-dialog-bersama-mahasiswa-penerima-kip-kuliah-di-aceh',
        ],
        [
            'title' => 'Motivasi penerima KIP Kuliah di Universitas Syiah Kuala',
            'image' => 'https://kemdiktisaintek.go.id/_next/image?url=%2Fapi%2Ffile%2Fhumas-production%2Fnews%2F2026%2F5%2Fimages%2F5bc85e35-4ab7-45a5-8854-6f26bc7feef7%2FWhatsApp-Image-2026-05-09-at-10.59.07-1024x681.jpeg&w=3840&q=75',
            'url' => 'https://kemdiktisaintek.go.id/news/article/wamendiktisaintek-beri-motivasi-penerima-kip-kuliah-di-usk',
        ],
        [
            'title' => 'Sosialisasi KIP Kuliah jalur SNBT',
            'image' => 'https://www.kemdiktisaintek.go.id/_next/image?url=%2Fapi%2Ffile%2Fhumas-production%2Fnews%2F2026%2F3%2Fimages%2F6fecda05-0707-407a-9b62-44115982ddbf%2FScreenshot_20260326_152708_YouTube.jpeg&w=3840&q=75',
            'url' => 'https://www.kemdiktisaintek.go.id/news/article/perluas-akses-pendidikan-tinggi-kemdiktisaintek-gelar-sosialisasi-kip-kuliah-jalur-snbt',
        ],
    ];
@endphp

<style>
    .kipk-dashboard-grid { display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(300px, .9fr); gap: 1rem; }
    .kipk-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: .5rem; padding: 1rem; }
    .kipk-chart-box { min-height: 310px; }
    .kipk-news-link { color: #0f172a; text-decoration: none; }
    .kipk-news-link:hover { color: #0d6efd; }
    .kipk-table-wrap { max-height: 360px; overflow: auto; }
    @media (max-width: 991px) { .kipk-dashboard-grid { grid-template-columns: 1fr; } }
</style>

<div class="content-card p-4 mt-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
        <div>
            <h2 class="h4 mb-1">Dashboard Nasional KIP Kuliah</h2>
            <p class="text-secondary mb-0">Sebaran penerima KIP Kuliah 2025 seluruh Indonesia dan berita resmi terkait KIP-K.</p>
        </div>
        <span class="badge text-bg-light border">Sumber: PPAPT/Kemdiktisaintek, data s.d. 31 Desember 2025</span>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="kipk-stat"><small>Total Penerima 2025</small><div class="fs-3 fw-bold">{{ number_format(1053851, 0, ',', '.') }}</div></div></div>
        <div class="col-md-4"><div class="kipk-stat"><small>Sasaran Penerima 2026</small><div class="fs-3 fw-bold">{{ number_format(1047221, 0, ',', '.') }}</div></div></div>
        <div class="col-md-4"><div class="kipk-stat"><small>Anggaran 2026</small><div class="fs-3 fw-bold">Rp15,3 T</div></div></div>
    </div>

    <div class="border rounded p-3 bg-white mb-3">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
            <h3 class="h6 mb-0">Dokumentasi KIP Kuliah</h3>
            <small class="text-secondary">Sumber: Kemdiktisaintek</small>
        </div>
        <div class="documentation-grid">
            @foreach($kipkDocumentation as $item)
                <figure class="documentation-card mb-0">
                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">
                    </a>
                    <figcaption>{{ $item['title'] }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>

    <div class="kipk-dashboard-grid">
        <div class="border rounded p-3 bg-white kipk-chart-box">
            <div class="d-flex justify-content-between gap-3 align-items-center mb-2">
                <h3 class="h6 mb-0">10 Provinsi Penerima Terbanyak</h3>
                <small class="text-secondary">Mahasiswa</small>
            </div>
            <canvas id="kipkProvinceChart" height="130"></canvas>
        </div>
        <div class="border rounded p-3 bg-white kipk-chart-box">
            <h3 class="h6 mb-2">Sebaran per Wilayah</h3>
            <canvas id="kipkRegionChart" height="170"></canvas>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <div class="border rounded bg-white p-3 h-100">
                <h3 class="h6 mb-3">Data Per Provinsi</h3>
                <div class="kipk-table-wrap">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Provinsi</th><th class="text-end">Penerima</th></tr></thead>
                        <tbody>
                            @foreach(collect($kipkProvinceData)->sortKeys() as $province => $total)
                                <tr><td>{{ $province }}</td><td class="text-end">{{ number_format($total, 0, ',', '.') }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="border rounded bg-white p-3 h-100">
                <h3 class="h6 mb-3">Berita Terkait KIP-K</h3>
                @foreach($kipkNews as $news)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between gap-3">
                            <small class="text-secondary">{{ $news['source'] }}</small>
                            <small class="text-secondary">{{ $news['date'] }}</small>
                        </div>
                        <a class="kipk-news-link fw-semibold d-block mt-1" href="{{ $news['url'] }}" target="_blank" rel="noopener">{{ $news['title'] }}</a>
                        <p class="text-secondary small mb-0 mt-1">{{ $news['summary'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="small text-secondary mt-3">
        Referensi:
        <a href="https://www.detik.com/edu/beasiswa/d-8465024/jumlah-penerima-kip-kuliah-tahun-2025-per-daerah-terbanyak-di-jatim" target="_blank" rel="noopener">sebaran penerima 2025</a>,
        <a href="https://www.kemdiktisaintek.go.id/news/article/anggaran-kip-kuliah-terus-meningkat-pemerintah-pastikan-akses-pendidikan-tinggi-tetap-terjaga" target="_blank" rel="noopener">anggaran dan sasaran 2026</a>,
        <a href="https://kemdiktisaintek.go.id/news/article/kemdiktisaintek-gelar-dialog-bersama-mahasiswa-penerima-kip-kuliah-di-aceh" target="_blank" rel="noopener">dokumentasi dialog KIP Kuliah</a>.
    </div>
</div>

@push('scripts')
<script>
(() => {
    const provinceCanvas = document.getElementById('kipkProvinceChart');
    const regionCanvas = document.getElementById('kipkRegionChart');
    if (!provinceCanvas || !regionCanvas || !window.Chart) return;

    new Chart(provinceCanvas, {
        type: 'bar',
        data: {
            labels: @json($kipkTopProvinces->keys()->values()),
            datasets: [{
                label: 'Penerima',
                data: @json($kipkTopProvinces->values()->values()),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { ticks: { callback: value => Number(value).toLocaleString('id-ID') } } }
        }
    });

    new Chart(regionCanvas, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($kipkRegions)),
            datasets: [{
                data: @json(array_values($kipkRegions)),
                backgroundColor: ['#0d6efd', '#16a34a', '#f59e0b', '#dc2626', '#7c3aed', '#0891b2']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
})();
</script>
@endpush
