<form method="post" action="{{ $action }}">
    @csrf
    @if($method === 'patch')
        @method('patch')
    @endif

    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label">Kriteria</label>
            <select name="criteria_id" class="form-select" required>
                @foreach($criteria as $criterion)
                    <option value="{{ $criterion->id }}" @selected(old('criteria_id', $testQuestion?->criteria_id) == $criterion->id)>
                        {{ $criterion->code }} - {{ $criterion->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $testQuestion?->sort_order ?? 0) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="isActive" @checked(old('is_active', $testQuestion?->is_active ?? true))>
                <label class="form-check-label" for="isActive">Soal aktif</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Pertanyaan</label>
            <textarea name="question" class="form-control" rows="4" required>{{ old('question', $testQuestion?->question) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pilihan A</label>
            <input name="option_a" class="form-control" value="{{ old('option_a', $testQuestion?->option_a) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pilihan B</label>
            <input name="option_b" class="form-control" value="{{ old('option_b', $testQuestion?->option_b) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pilihan C</label>
            <input name="option_c" class="form-control" value="{{ old('option_c', $testQuestion?->option_c) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Pilihan D</label>
            <input name="option_d" class="form-control" value="{{ old('option_d', $testQuestion?->option_d) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kunci Jawaban</label>
            <select name="correct_answer" class="form-select" required>
                @foreach(['A', 'B', 'C', 'D'] as $answer)
                    <option value="{{ $answer }}" @selected(old('correct_answer', $testQuestion?->correct_answer ?? 'A') === $answer)>{{ $answer }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button class="btn btn-primary mt-3">Simpan</button>
    <a class="btn btn-outline-secondary mt-3" href="{{ route('test-questions.index') }}">Batal</a>
</form>
