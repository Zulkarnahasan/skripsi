<form method="post" action="{{ $action }}">@csrf @if($method==='patch') @method('patch') @endif
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Kriteria</label><select name="criteria_id" class="form-select">@foreach($criteria as $criterion)<option value="{{ $criterion->id }}" @selected(old('criteria_id',$subCriterion?->criteria_id)==$criterion->id)>{{ $criterion->code }} - {{ $criterion->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name',$subCriterion?->name) }}" required></div>
        <div class="col-md-2"><label class="form-label">Min</label><input name="min_value" type="number" step="0.01" class="form-control" value="{{ old('min_value',$subCriterion?->min_value) }}"></div>
        <div class="col-md-2"><label class="form-label">Max</label><input name="max_value" type="number" step="0.01" class="form-control" value="{{ old('max_value',$subCriterion?->max_value) }}"></div>
        <div class="col-md-1"><label class="form-label">Skor</label><input name="score" type="number" step="0.0001" class="form-control" value="{{ old('score',$subCriterion?->score) }}" required></div>
    </div><button class="btn btn-primary mt-3">Simpan</button>
</form>
