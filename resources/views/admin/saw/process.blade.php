@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <h1 class="h4">Input Nilai Alternatif</h1>
    <form method="post" action="{{ route('saw.scores') }}">@csrf
        <div class="table-responsive"><table class="table"><thead><tr><th>Pendaftar</th>@foreach($criteria as $criterion)<th>{{ $criterion->code }}</th>@endforeach</tr></thead><tbody>
            @foreach($alternatives as $alternative)<tr><td>{{ $alternative->user->name }}</td>@foreach($criteria as $criterion)@php($score=$alternative->scores->firstWhere('criteria_id',$criterion->id))<td><input class="form-control" type="number" step="0.0001" name="scores[{{ $alternative->id }}][{{ $criterion->id }}]" value="{{ $score?->score ?? 0 }}"></td>@endforeach</tr>@endforeach
        </tbody></table></div>
        <button class="btn btn-primary">Simpan Nilai</button>
    </form>
    <form method="post" action="{{ route('saw.calculate') }}" class="row g-2 mt-4" data-ajax-saw>@csrf
        <div class="col-md-3"><input name="quota" type="number" class="form-control" value="10" min="1"></div>
        <div class="col-md-3"><button class="btn btn-success w-100">Proses SAW</button></div>
        <div class="col-md-6"><span data-loading class="text-secondary d-none">Menghitung...</span></div>
    </form>
</div>
@endsection
