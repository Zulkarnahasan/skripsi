@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex justify-content-between"><h1 class="h4">Laporan Ranking</h1><a class="btn btn-outline-secondary" target="_blank" href="{{ route('reports.print') }}">Cetak</a></div>
    @include('admin.saw.table',['results'=>$results])
</div>
@endsection
