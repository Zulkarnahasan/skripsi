@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Tes Pendaftar</div>
            <h1 class="h4 mb-0">Jawaban Tes</h1>
        </div>
        <span class="badge badge-soft">{{ $alternatives->total() }} pendaftar</span>
    </div>

    <form method="get" action="{{ route('test-answers.index') }}" class="data-toolbar">
        <div>
            <label class="form-label visually-hidden" for="searchAnswer">Cari jawaban tes</label>
            <input
                id="searchAnswer"
                name="q"
                class="form-control search-input"
                value="{{ request('q') }}"
                placeholder="Cari nama, email, NISN, No. KIP, No. WA..."
            >
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Cari</button>
            @if(request('q'))
                <a class="btn btn-outline-secondary" href="{{ route('test-answers.index') }}">Reset</a>
            @endif
        </div>
    </form>

    <div class="accordion" id="testAnswersAccordion">
        @forelse($alternatives as $alternative)
            @php
                $answers = $alternative->testAnswers->sortBy(fn ($answer) => [$answer->question?->sort_order ?? 0, $answer->question?->id ?? 0]);
                $correctTotal = $answers->filter(fn ($answer) => (float) $answer->score > 0)->count();
                $criteriaScores = $alternative->scores
                    ->sortBy(fn ($score) => $score->criteria?->code ?? $score->criteria_id)
                    ->map(function ($score) use ($answers) {
                        $criteriaAnswers = $answers->filter(fn ($answer) => $answer->question?->criteria_id === $score->criteria_id);

                        return [
                            'score' => $score,
                            'answers' => $criteriaAnswers,
                            'correct' => $criteriaAnswers->filter(fn ($answer) => (float) $answer->score > 0)->count(),
                        ];
                    });
                $collapseId = 'answer-'.$alternative->id;
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
                            <span class="badge text-bg-primary">{{ $correctTotal }}/{{ $answers->count() }} benar</span>
                        </span>
                    </button>
                </h2>
                <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $collapseId }}" data-bs-parent="#testAnswersAccordion">
                    <div class="accordion-body">
                        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
                            <div>
                                <h3 class="h6 mb-1">Nilai Per Kriteria</h3>
                                <div class="text-secondary small">Ringkasan nilai tes tanpa menampilkan seluruh soal.</div>
                            </div>
                            <span class="badge badge-soft">Total nilai tes: {{ number_format((float) $answers->sum('score'), 0) }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-modern align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kriteria</th>
                                        <th>Jumlah Soal</th>
                                        <th>Benar</th>
                                        <th>Nilai</th>
                                        <th>Normalisasi</th>
                                        <th>Nilai Terbobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criteriaScores as $item)
                                        @php($score = $item['score'])
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $score->criteria?->code }}</strong>
                                                <div class="text-secondary small">{{ $score->criteria?->name }}</div>
                                            </td>
                                            <td>{{ $item['answers']->count() }}</td>
                                            <td>{{ $item['correct'] }}</td>
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
            <div class="text-center text-secondary py-4">Belum ada jawaban tes yang masuk.</div>
        @endforelse
    </div>

    <div class="mt-3">{{ $alternatives->links() }}</div>
</div>
@endsection
