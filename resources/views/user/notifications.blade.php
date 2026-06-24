@extends('layouts.user')

@section('user-content')
<div class="content-card p-4">
    <h1 class="h4">Notifikasi</h1>
    @foreach($notifications as $notification)
        <div class="border-bottom py-2"><strong>{{ $notification->title }}</strong><p class="mb-0 text-secondary">{{ $notification->message }}</p></div>
    @endforeach
    {{ $notifications->links() }}
</div>
@endsection
