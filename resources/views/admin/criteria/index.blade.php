@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex justify-content-between"><h1 class="h4">Kriteria SAW</h1><a class="btn btn-primary" href="{{ route('criteria.create') }}">Tambah</a></div>
    <table class="table mt-3"><thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>Bobot</th><th></th></tr></thead><tbody>
        @foreach($criteria as $criterion)<tr><td>{{ $criterion->code }}</td><td>{{ $criterion->name }}</td><td>{{ $criterion->type }}</td><td>{{ number_format((float) $criterion->weight * 100, 2, ',', '.') }}%</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('criteria.edit',$criterion) }}">Edit</a><form method="post" action="{{ route('criteria.destroy',$criterion) }}" class="d-inline">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>@endforeach
    </tbody></table>{{ $criteria->links() }}
</div>
@endsection
