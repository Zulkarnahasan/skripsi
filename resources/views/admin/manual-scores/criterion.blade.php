@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Penilaian Manual</div>
            <h1 class="h4 mb-1">{{ $title }}</h1>
            <p class="text-secondary mb-0">{{ $description }}</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('saw.process') }}">Proses SAW</a>
    </div>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-8">
            <label class="form-label visually-hidden" for="searchCriterionScore">Cari pendaftar</label>
            <input id="searchCriterionScore" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari nama, NISN, NPSN, nomor KIP, atau nomor WA">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-primary flex-grow-1">Cari</button>
            @if(request()->filled('q'))
                <a class="btn btn-outline-secondary" href="{{ url()->current() }}">Reset</a>
            @endif
        </div>
    </form>

    @if(! $criterion)
        <div class="text-center text-secondary py-4">Kriteria belum tersedia.</div>
    @elseif($alternatives->isEmpty())
        <div class="text-center text-secondary py-4">Belum ada pendaftar terverifikasi.</div>
    @else
        <div class="alert alert-info">
            Isi setiap aspek dengan angka 0 sampai 100. Nilai akhir {{ $criterion->name }} dihitung otomatis dari rata-rata seluruh aspek.
        </div>

        <form method="post" action="{{ route($storeRoute) }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Pendaftar</th>
                            <th>NISN</th>
                            <th>Asal Sekolah</th>
                            @foreach($components as $component)
                                <th>
                                    {{ $component }}
                                    <div class="text-secondary small">0 sampai 100</div>
                                </th>
                            @endforeach
                            <th>Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                            @php($score = $alternative->scores->firstWhere('criteria_id', $criterion->id))
                            @php($componentScores = $alternative->manualAssessmentScores->keyBy('component_key'))
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $alternative->user->name }}</div>
                                    <div class="text-secondary small">{{ $alternative->registration_number }}</div>
                                </td>
                                <td>{{ $alternative->studentProfile?->nisn ?? '-' }}</td>
                                <td>{{ $alternative->studentProfile?->school_origin ?? '-' }}</td>
                                @foreach($components as $componentKey => $component)
                                    @php($componentScore = $componentScores->get($componentKey))
                                    <td style="min-width: 150px;">
                                        <label class="form-label visually-hidden" for="score-{{ $alternative->id }}-{{ $componentKey }}">
                                            {{ $component }} {{ $alternative->user->name }}
                                        </label>
                                        <input
                                            id="score-{{ $alternative->id }}-{{ $componentKey }}"
                                            class="form-control"
                                            type="number"
                                            min="0"
                                            max="100"
                                            step="1"
                                            name="scores[{{ $alternative->id }}][{{ $componentKey }}]"
                                            value="{{ old('scores.'.$alternative->id.'.'.$componentKey, round((float) ($componentScore?->score ?? 0))) }}"
                                        >
                                    </td>
                                @endforeach
                                <td>
                                    <span class="badge text-bg-primary">{{ number_format((float) ($score?->score ?? 0), 0, ',', '.') }}/100</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                <div>{{ $alternatives->links() }}</div>
                <button class="btn btn-primary">Simpan {{ $title }}</button>
            </div>
        </form>
    @endif
</div>
@endsection
