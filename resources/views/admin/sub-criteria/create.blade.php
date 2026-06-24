@extends('layouts.admin')
@section('admin-content')<div class="content-card p-4"><h1 class="h4">Tambah Sub-kriteria</h1>@include('admin.sub-criteria.form',['action'=>route('sub-criteria.store'),'method'=>'post','subCriterion'=>null])</div>@endsection
