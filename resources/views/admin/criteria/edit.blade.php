@extends('layouts.admin')
@section('admin-content')<div class="content-card p-4"><h1 class="h4">Edit Kriteria</h1>@include('admin.criteria.form',['action'=>route('criteria.update',$criterion),'method'=>'patch'])</div>@endsection
