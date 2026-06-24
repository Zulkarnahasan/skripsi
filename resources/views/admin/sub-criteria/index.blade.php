@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex justify-content-between"><h1 class="h4">Sub-kriteria</h1><a class="btn btn-primary" href="{{ route('sub-criteria.create') }}">Tambah</a></div>
    <table class="table mt-3"><thead><tr><th>Kriteria</th><th>Nama</th><th>Range</th><th>Skor</th><th></th></tr></thead><tbody>
        @foreach($subCriteria as $item)<tr><td>{{ $item->criteria->code }}</td><td>{{ $item->name }}</td><td>{{ $item->min_value }} - {{ $item->max_value }}</td><td>{{ $item->score }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('sub-criteria.edit',$item) }}">Edit</a><form method="post" action="{{ route('sub-criteria.destroy',$item) }}" class="d-inline">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>@endforeach
    </tbody></table>{{ $subCriteria->links() }}
</div>
@endsection
