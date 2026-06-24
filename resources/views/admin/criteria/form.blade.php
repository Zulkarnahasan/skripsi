<form method="post" action="{{ $action }}">@csrf @if($method==='patch') @method('patch') @endif
    <div class="row g-3">
        <div class="col-md-2"><label class="form-label">Kode</label><input name="code" class="form-control" value="{{ old('code',$criterion?->code) }}" required></div>
        <div class="col-md-4"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name',$criterion?->name) }}" required></div>
        <div class="col-md-3"><label class="form-label">Jenis</label><select name="type" class="form-select"><option value="benefit" @selected(old('type',$criterion?->type)==='benefit')>Benefit</option><option value="cost" @selected(old('type',$criterion?->type)==='cost')>Cost</option></select></div>
        <div class="col-md-3"><label class="form-label">Bobot (%)</label><input name="weight" type="number" min="0" max="100" step="0.01" class="form-control" value="{{ old('weight', $criterion ? (float) $criterion->weight * 100 : null) }}" required></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control">{{ old('description',$criterion?->description) }}</textarea></div>
    </div><button class="btn btn-primary mt-3">Simpan</button>
</form>
