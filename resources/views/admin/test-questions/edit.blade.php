@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="hero-kicker">Bank Soal</div>
    <h1 class="h4 mb-3">Edit Soal Tes</h1>
    @include('admin.test-questions.form', [
        'action' => route('test-questions.update', $testQuestion),
        'method' => 'patch',
    ])
</div>
@endsection
