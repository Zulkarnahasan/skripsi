@extends('layouts.admin')
@section('admin-content')<div class="content-card p-4"><h1 class="h4">Edit Sub-kriteria</h1>@include('admin.sub-criteria.form',['action'=>route('sub-criteria.update',$subCriterion),'method'=>'patch'])</div>@endsection
