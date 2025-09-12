@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">Archived Inspeksi</h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>

<div class="p-3 sm:p-6">
    <div class="flex justify-between gap-3 flex-wrap mb-6">
        <a href="{{ route('history') }}" class="btn btn-outline"><i class="ri-arrow-left-line"></i> <span>KEMBALI</span></a>
        <form method="GET" class="join">
            <button type="submit" class="btn btn-primary btn-square join-item">
                <i class="ri-search-line"></i>
            </button>
            <input type="text" name="s" class="input input-bordered join-item w-full" placeholder="Cari berdasarkan nama tempat..." value="{{ request('s') }}" />
        </form>
    </div>

    <div class="overflow-x-auto mb-2 bg-white rounded-lg">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th></th>
                    <th>Nama Tempat</th>
                    <th>Nama Pemeriksa</th>
                    <th>Skor</th>
                    <th>Tanggal Pemeriksaan</th>
                    <th>Status Operasi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if (count($inspections) == 0)
                <tr>
                    <td colspan="7" class="text-center">Tidak ada hasil inspeksi yang dapat ditampilkan</td>
                </tr>
                @endif
                @foreach ($inspections as $index => $inspection)
                <tr>
                    <th>{{ $index + 1 }}</th>
                    <td><i class="{{ $inspection['icon'] }} text-{{ $inspection['color'] }}"></i> <span>{{ $inspection['name'] }}</span></td>
                    <td>{{ $inspection['reviewer'] }}</td>
                    <td class="font-semibold">{{ number_format($inspection['score'], 0, ',', '.') }}</td>
                    <td>{{ $inspection['date'] }}</td>
                    <td>
                        @if ($inspection['operasi'])
                        <p class="border-success border text-success font-medium px-3 py-1.5 rounded-full text-xs text-center">MASIH BEROPERASI</p>
                        @else
                        <p class="border-error border text-error font-medium px-1.5 py-1 rounded-full text-xs text-center">TIDAK BEROPERASI</p>
                        @endif
                    </td>
                    <td class="flex gap-1.5">
                        <div class="tooltip tooltip-success" data-tip="Unarchive Inspeksi">
                            <button type="button" class="btn btn-success btn-outline btn-square" onclick="showUnarchiveConfirmation('{{ $inspection['name'] }}', '{{ $inspection['sud'] }}')">
                                <i class="ri-inbox-unarchive-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-modal.confirmation />

<script>
    function showUnarchiveConfirmation(inspectionName, sudUrl) {
        showConfirmationModal(
            'Pemulihan Hasil Inspeksi',
            `Apakah Anda yakin ingin memulihkan hasil inspeksi "${inspectionName}"? Data akan dipindahkan kembali ke histori inspeksi.`,
            function() {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('/') }}/' + sudUrl;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method override for DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        );
    }
</script>
@endsection
