@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <h1 class="h4">Kelola Notifikasi</h1>
    <form method="post" action="{{ route('admin.notifications') }}" class="row g-2 mb-4">@csrf
        <div class="col-md-3"><select name="user_id" class="form-select"><option value="">Semua user</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><input name="title" class="form-control" placeholder="Judul" required></div>
        <div class="col-md-4"><input name="message" class="form-control" placeholder="Pesan" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Kirim</button></div>
    </form>
    @foreach($notifications as $notification)<div class="border-bottom py-2"><strong>{{ $notification->title }}</strong> <small>{{ $notification->user?->name ?? 'Semua' }}</small><p class="mb-0">{{ $notification->message }}</p></div>@endforeach
    {{ $notifications->links() }}
</div>
@endsection
