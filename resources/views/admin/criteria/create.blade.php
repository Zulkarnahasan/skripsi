@extends('layouts.admin')
@section('admin-content')<div class="content-card p-4"><h1 class="h4">Tambah Kriteria</h1>@include('admin.criteria.form',['action'=>route('criteria.store'),'method'=>'post','criterion'=>null])</div>@endsection
