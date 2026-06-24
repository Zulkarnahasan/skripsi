@extends('layouts.admin')

@section('admin-content')
<div class="content-card p-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3">
        <div>
            <div class="hero-kicker">Manajemen Akun</div>
            <h1 class="h4 mb-0">Data User</h1>
        </div>
        <span class="badge badge-soft">{{ $users->total() }} user</span>
    </div>

    <form method="get" action="{{ route('admin.users') }}" class="data-toolbar">
        <div class="d-flex flex-wrap gap-2">
            <label class="form-label visually-hidden" for="searchUser">Cari user</label>
            <input id="searchUser" name="q" class="form-control search-input" value="{{ request('q') }}" placeholder="Cari nama, email, NISN, No. KIP, No. WA...">
            <select name="entry_year" class="form-select" aria-label="Filter tahun masuk" style="max-width: 190px;">
                <option value="">Semua tahun masuk</option>
                @foreach($entryYears as $year)
                    <option value="{{ $year }}" @selected((string) request('entry_year') === (string) $year)>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Cari</button>
            @if(request('q') || request('entry_year'))
                <a class="btn btn-outline-secondary" href="{{ route('admin.users') }}">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Asal Sekolah</th>
                    <th>Tahun Masuk</th>
                    <th>No. WA</th>
                    <th>Status Akun</th>
                    <th>Daftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            <div class="text-secondary small">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->studentProfile?->nisn ?? '-' }}</td>
                        <td>{{ $user->studentProfile?->school_origin ?? '-' }}</td>
                        <td>{{ $user->studentProfile?->entry_year ?? '-' }}</td>
                        <td>{{ $user->studentProfile?->phone ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'text-bg-success' : 'text-bg-danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                @if($user->is_active)
                                    <form method="post" action="{{ route('admin.users.deactivate', $user) }}">
                                        @csrf
                                        @method('patch')
                                        <button class="btn btn-sm btn-outline-warning" onclick="return confirm('Nonaktifkan akun user ini? User tidak akan bisa login.')">Nonaktifkan</button>
                                    </form>
                                @else
                                    <form method="post" action="{{ route('admin.users.activate', $user) }}">
                                        @csrf
                                        @method('patch')
                                        <button class="btn btn-sm btn-outline-success">Aktifkan</button>
                                    </form>
                                @endif
                                <form method="post" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus akun user ini secara permanen? Data profil, jawaban tes, dokumen, dan hasil terkait juga akan terhapus.')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">Data user tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
