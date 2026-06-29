@php($canEditStatus = ! request()->routeIs('reports.*'))
<table class="table mt-3"><thead><tr><th>Rank</th><th>Pendaftar</th><th>Nilai Akhir</th><th>Status</th>@if($canEditStatus)<th class="text-end">Ubah Status</th>@endif</tr></thead><tbody>
@foreach($results as $result)
@php($statusLabel = ['accepted' => 'Lulus', 'rejected' => 'Tidak Lulus'][$result->status] ?? $result->status)
@php($statusClass = $result->status === 'accepted' ? 'text-bg-success' : 'text-bg-danger')
<tr>
    <td>{{ $result->rank }}</td>
    <td>{{ $result->alternative->user->name }}</td>
    <td>{{ number_format((float) $result->final_score * 100, 2, ',', '.') }}/100</td>
    <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
    @if($canEditStatus)
        <td class="text-end">
            <form method="post" action="{{ route('saw.results.status', $result) }}" class="d-inline-flex gap-2 align-items-center">
                @csrf
                @method('patch')
                <select name="status" class="form-select form-select-sm" aria-label="Ubah status {{ $result->alternative->user->name }}">
                    <option value="accepted" @selected($result->status === 'accepted')>Lulus</option>
                    <option value="rejected" @selected($result->status === 'rejected')>Tidak Lulus</option>
                </select>
                <button class="btn btn-sm btn-primary">Simpan</button>
            </form>
        </td>
    @endif
</tr>
@endforeach
</tbody></table>
@if(method_exists($results,'links')) {{ $results->links() }} @endif
