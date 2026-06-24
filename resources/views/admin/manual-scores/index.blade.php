@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Nilai Manual SAW</div>
            <h1 class="h4 mb-0">Input Nilai Wawancara dan Baca Quran</h1>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('saw.process') }}">Input Nilai SAW</a>
    </div>

    <form method="get" action="{{ route('manual-scores.index') }}" class="row g-2 mb-3">
        <div class="col-md-8">
            <label class="form-label visually-hidden" for="searchManualScore">Cari pendaftar</label>
            <input id="searchManualScore" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari nama, NISN, NPSN, nomor KIP, atau nomor WA">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-primary flex-grow-1">Cari</button>
            @if(request()->filled('q'))
                <a class="btn btn-outline-secondary" href="{{ route('manual-scores.index') }}">Reset</a>
            @endif
        </div>
    </form>

    <div class="alert alert-info">
        Isi nilai 0 sampai 100 untuk pendaftar yang sudah terverifikasi. Nilai ini akan ikut dihitung saat admin menjalankan Proses SAW.
    </div>

    @if($criteria->isEmpty())
        <div class="text-center text-secondary py-4">Kriteria Wawancara dan Baca Quran belum tersedia. Jalankan migration terlebih dahulu.</div>
    @elseif($alternatives->isEmpty())
        <div class="text-center text-secondary py-4">Belum ada pendaftar terverifikasi.</div>
    @else
        <form method="post" action="{{ route('manual-scores.store') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Pendaftar</th>
                            <th>NISN</th>
                            <th>Asal Sekolah</th>
                            @foreach($criteria as $criterion)
                                <th>
                                    {{ $criterion->name }}
                                    <div class="text-secondary small">Bobot {{ number_format((float) $criterion->weight * 100, 2, ',', '.') }}%</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $alternative->user->name }}</div>
                                    <div class="text-secondary small">{{ $alternative->registration_number }}</div>
                                </td>
                                <td>{{ $alternative->studentProfile?->nisn ?? '-' }}</td>
                                <td>{{ $alternative->studentProfile?->school_origin ?? '-' }}</td>
                                @foreach($criteria as $criterion)
                                    @php($score = $alternative->scores->firstWhere('criteria_id', $criterion->id))
                                    <td style="min-width: 160px;">
                                        <label class="form-label visually-hidden" for="score-{{ $alternative->id }}-{{ $criterion->id }}">
                                            {{ $criterion->name }} {{ $alternative->user->name }}
                                        </label>
                                        <input
                                            id="score-{{ $alternative->id }}-{{ $criterion->id }}"
                                            class="form-control"
                                            type="number"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            name="scores[{{ $alternative->id }}][{{ $criterion->id }}]"
                                            value="{{ old('scores.'.$alternative->id.'.'.$criterion->id, $score?->score ?? 0) }}"
                                        >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                <div>{{ $alternatives->links() }}</div>
                <button class="btn btn-primary">Simpan Nilai Manual</button>
            </div>
        </form>
    @endif
</div>
@endsection
