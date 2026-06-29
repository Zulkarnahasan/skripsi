@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex justify-content-between"><h1 class="h4">Hasil SAW</h1></div>
    @include('admin.saw.table',['results'=>$results])
</div>
@endsection
