@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show auto-alert">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger auto-alert">{{ $errors->first() }}</div>
@endif
