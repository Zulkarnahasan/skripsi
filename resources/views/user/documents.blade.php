@extends('layouts.user')

@section('user-content')
<div class="content-card p-4" data-reveal>
    <div class="data-toolbar">
        <div>
            <div class="hero-kicker">Berkas Pendaftaran</div>
            <h1 class="h4 mb-1">Upload Dokumen</h1>
            <p class="text-secondary mb-0">Format yang diterima: PDF dengan ukuran maksimal 10 MB.</p>
        </div>
        <input class="form-control search-input" data-live-search placeholder="Cari dokumen...">
    </div>

    <div class="table-responsive">
        <table class="table table-modern table-searchable align-middle mb-0">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Upload</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documentTypes as $type => $meta)
                    @php
                        $document = $documents->get($type);
                        $statusLabels = [
                            'pending' => 'Menunggu verifikasi',
                            'verified' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                        ];
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $meta['label'] }}</td>
                        <td>
                            @if($meta['required'])
                                <span class="badge text-bg-danger">Wajib</span>
                            @else
                                <span class="badge text-bg-secondary">Jika Ada</span>
                            @endif
                        </td>
                        <td>
                            @if($document)
                                <a class="soft-button py-1" href="{{ asset('storage/'.$document->file_path) }}" target="_blank" rel="noopener">Lihat File</a>
                            @else
                                <span class="text-secondary">Belum diunggah</span>
                            @endif
                        </td>
                        <td>
                            @if($document)
                                <span class="badge badge-soft">{{ $statusLabels[$document->status] ?? $document->status }}</span>
                            @else
                                <span class="badge badge-soft">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            <form method="post" action="{{ route('user.documents') }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center">
                                @csrf
                                <input type="hidden" name="document_type" value="{{ $type }}">
                                <input name="file" type="file" class="form-control form-control-sm" accept=".pdf,application/pdf" required>
                                <button class="btn btn-sm btn-primary">{{ $document ? 'Upload Ulang' : 'Upload' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
