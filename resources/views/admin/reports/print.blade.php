<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Ranking KIP-K</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page { size: A4; margin: 14mm 18mm; }
        body { color: #111827; background: #fff; font-family: "Times New Roman", Times, serif; }
        .print-page { width: 174mm; margin: 0 auto; }
        .letterhead { text-align: center; margin-bottom: 8mm; }
        .letterhead img { width: 150mm; max-width: 100%; height: auto; display: block; margin: 0 auto; }
        .report-title { text-align: center; margin: 6mm 0 .25rem; font-weight: 700; text-transform: uppercase; text-decoration: underline; }
        .report-meta { text-align: center; margin-bottom: 1rem; font-size: .95rem; }
        table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        .table > :not(caption) > * > * { background: #fff !important; color: #111827 !important; }
        th, td { border: 1px solid #111827 !important; padding: .45rem .55rem !important; vertical-align: middle; }
        th { text-align: center; font-weight: 700; }
        .badge { border: 0; color: #111827 !important; background: transparent !important; padding: 0; font-size: inherit; }
        .text-end form { display: none !important; }
        .print-actions { display: flex; justify-content: flex-end; gap: .5rem; margin-bottom: 1rem; }
        @media print {
            .print-actions { display: none !important; }
            .print-page { width: 174mm; }
        }
    </style>
</head>
<body>
    <main class="print-page py-3">
        <div class="print-actions">
            <button class="btn btn-primary btn-sm" onclick="window.print()">Cetak / Save PDF</button>
        </div>

        <header class="letterhead">
            <img src="{{ asset('images/kop-surat-permakip.jpeg') }}" alt="Kop surat PERMAKIP UMT">
        </header>

        <h1 class="h5 report-title">Laporan Ranking Seleksi KIP-K</h1>
        <div class="report-meta">Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</div>

        @include('admin.saw.table', ['results' => $results])
    </main>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
