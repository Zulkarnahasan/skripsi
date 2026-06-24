@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Bank Soal</div>
            <h1 class="h4 mb-0">Soal Tes per Kriteria</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('test-questions.create') }}">Tambah Soal</a>
    </div>

    <form method="post" action="{{ route('test-questions.settings') }}" class="border rounded bg-white p-3 mb-4">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Durasi Tes</label>
                <div class="input-group">
                    <input name="duration_minutes" type="number" min="1" max="600" class="form-control" value="{{ old('duration_minutes', $setting->duration_minutes) }}" required>
                    <span class="input-group-text">menit</span>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Akses Tes</label>
                <input type="hidden" name="is_open" value="0">
                <div class="form-check form-switch border rounded px-5 py-2 bg-light">
                    <input class="form-check-input" type="checkbox" role="switch" id="isOpen" name="is_open" value="1" @checked(old('is_open', $setting->is_open))>
                    <label class="form-check-label" for="isOpen">{{ $setting->is_open ? 'Tes dibuka' : 'Tes ditutup' }}</label>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100">Simpan Pengaturan</button>
            </div>
            <div class="col-12">
                <label class="form-label">Instruksi untuk User</label>
                <textarea name="instruction" rows="3" class="form-control">{{ old('instruction', $setting->instruction) }}</textarea>
            </div>
        </div>
    </form>

    <div class="accordion" id="questionGroups">
        @foreach($criteria as $criterion)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#criteria-group-{{ $criterion->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="criteria-group-{{ $criterion->id }}">
                        <span class="fw-semibold">{{ $criterion->code }} - {{ $criterion->name }}</span>
                        <span class="badge badge-soft ms-2">{{ $criterion->testQuestions->count() }} soal</span>
                        <span class="badge badge-soft ms-2">{{ number_format((float) $criterion->weight * 100, 2, ',', '.') }}%</span>
                    </button>
                </h2>
                <div id="criteria-group-{{ $criterion->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#questionGroups">
                    <div class="accordion-body">
                        @if($criterion->testQuestions->isEmpty())
                            <div class="text-center text-secondary py-4">Belum ada soal untuk kriteria ini.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-modern align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Urutan</th>
                                            <th>Soal</th>
                                            <th>Kunci</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criterion->testQuestions as $question)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $question->sort_order }}</td>
                                                <td>
                                                    <div class="fw-semibold">{{ $question->question }}</div>
                                                    <div class="small text-secondary mt-1">
                                                        A. {{ $question->option_a }} | B. {{ $question->option_b }} | C. {{ $question->option_c }} | D. {{ $question->option_d }}
                                                    </div>
                                                </td>
                                                <td><span class="badge text-bg-primary">{{ $question->correct_answer }}</span></td>
                                                <td><span class="badge {{ $question->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $question->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                                <td class="text-end">
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('test-questions.edit', $question) }}">Edit</a>
                                                    <form method="post" action="{{ route('test-questions.destroy', $question) }}" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
