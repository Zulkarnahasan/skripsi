@extends('layouts.admin')

@section('admin-content')
@php
    $sortLabels = [
        'abjad' => 'Sesuai abjad',
        'ranking' => 'Sesuai ranking',
        'tanggal_diubah' => 'Tanggal diubah',
    ];
@endphp

<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Perhitungan SAW</div>
            <h1 class="h4 mb-0">Nilai Terhitung</h1>
        </div>
        <span class="badge badge-soft">{{ $alternatives->total() }} pendaftar</span>
    </div>

    <form method="get" action="{{ route('saw.normalization') }}" class="data-toolbar">
        <div>
            <label class="form-label visually-hidden" for="searchCalculatedScore">Cari nilai terhitung</label>
            <input
                id="searchCalculatedScore"
                name="q"
                class="form-control search-input"
                value="{{ request('q') }}"
                placeholder="Cari nama, email, NISN, No. KIP, No. WA..."
            >
            <input type="hidden" name="sort" value="{{ $sort }}">
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Filter: {{ $sortLabels[$sort] ?? $sortLabels['tanggal_diubah'] }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($sortLabels as $sortKey => $sortLabel)
                        <li>
                            <a
                                class="dropdown-item {{ $sort === $sortKey ? 'active' : '' }}"
                                href="{{ route('saw.normalization', array_filter(['q' => request('q'), 'sort' => $sortKey])) }}"
                            >
                                {{ $sortLabel }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <button class="btn btn-primary" type="submit">Cari</button>
            @if(request('q') || $sort !== 'tanggal_diubah')
                <a class="btn btn-outline-secondary" href="{{ route('saw.normalization') }}">Reset</a>
            @endif
        </div>
    </form>

    <div class="accordion" id="calculatedScoresAccordion">
        @forelse($alternatives as $alternative)
            @php
                $scores = $alternative->scores->sortBy(fn ($score) => $score->criteria?->code ?? $score->criteria_id);
                $collapseId = 'calculated-'.$alternative->id;
            @endphp
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="heading-{{ $collapseId }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                        <span class="d-flex flex-wrap justify-content-between gap-3 align-items-center w-100 pe-3">
                            <span>
                                <span class="fw-bold d-block">{{ $alternative->user?->name }}</span>
                                <span class="text-secondary small">
                                    {{ $alternative->registration_number }} &middot; NISN {{ $alternative->studentProfile?->nisn ?? '-' }}
                                </span>
                            </span>
                            <span class="d-flex flex-wrap gap-2">
                                <span class="badge text-bg-primary">Nilai akhir {{ number_format((float) ($alternative->sawResult?->final_score ?? $scores->sum('weighted_value')), 4) }}</span>
                                @if($alternative->sawResult?->rank)
                                    <span class="badge badge-soft">Rank {{ $alternative->sawResult->rank }}</span>
                                @endif
                            </span>
                        </span>
                    </button>
                </h2>
                <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $collapseId }}" data-bs-parent="#calculatedScoresAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-modern align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kriteria</th>
                                        <th>Nilai Awal</th>
                                        <th>Skor</th>
                                        <th>Normalisasi</th>
                                        <th>Nilai Terbobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scores as $score)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $score->criteria?->code }}</strong>
                                                <div class="text-secondary small">{{ $score->criteria?->name }}</div>
                                            </td>
                                            <td>{{ number_format((float) $score->raw_value, 2) }}</td>
                                            <td>{{ number_format((float) $score->score, 2) }}</td>
                                            <td>{{ number_format((float) $score->normalized_value, 4) }}</td>
                                            <td>
                                                <span class="badge text-bg-primary">{{ number_format((float) $score->weighted_value, 4) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-secondary py-4">Belum ada nilai terhitung.</div>
        @endforelse
    </div>

    <div class="mt-3">{{ $alternatives->links() }}</div>
</div>
@endsection
