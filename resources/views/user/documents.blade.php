@extends('layouts.user')

@section('user-content')
<div class="content-card p-4" data-reveal>
    <div class="data-toolbar">
        <div>
            <div class="hero-kicker">Berkas Pendaftaran</div>
            <h1 class="h4 mb-1">Upload Dokumen</h1>
            <p class="text-secondary mb-0">Format yang diterima: PDF, JPG, JPEG, atau PNG dengan ukuran maksimal 2 MB.</p>
        </div>
        <input class="form-control search-input" data-live-search placeholder="Cari dokumen...">
    </div>

    <form method="post" action="{{ route('user.documents') }}" enctype="multipart/form-data" class="row g-3 mb-4">
        @csrf
        <div class="col-lg-4">
            <label class="form-label">Jenis Dokumen</label>
            <input name="document_type" class="form-control" placeholder="KTP, KKS, Rapor, dll" required>
        </div>
        <div class="col-lg-5">
            <label class="form-label">File</label>
            <div class="drop-zone" data-drop-zone>
                <input name="file" type="file" class="form-control" data-file-preview accept=".pdf,.jpg,.jpeg,.png" required>
                <small id="filePreview" class="d-block text-secondary mt-2">Pilih file atau tarik file ke area ini.</small>
            </div>
        </div>
        <div class="col-lg-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Upload</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-modern table-searchable align-middle mb-0">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>File</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                    <tr>
                        <td class="fw-semibold">{{ $document->document_type }}</td>
                        <td><a class="soft-button py-1" href="{{ asset('storage/'.$document->file_path) }}" target="_blank" rel="noopener">Lihat File</a></td>
                        <td><span class="badge badge-soft">{{ $document->status }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-secondary py-4">Belum ada dokumen yang diunggah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
