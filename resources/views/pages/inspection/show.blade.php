@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">Hasil Inspeksi {{ $inspection_name }}</h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>

<div class="px-3 sm:px-6 py-3">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a class="text-blue-500" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a class="text-blue-500" href="{{ route('history') }}">Histori Hasil Inspeksi</a></li>
            <li>Hasil Penilaian</li>
        </ul>
    </div>
</div>

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Laporan Inspeksi</h1>
        <hr class="my-5" />
        <div class="grid grid-flow-row md:grid-cols-2 gap-5">
            @foreach ($general_info as $input)
            @if (($inspection_name == "Gerai Pangan Jajanan Keliling Gol A1" || $inspection_name == "Gerai Pangan Jajanan Keliling Gol A2") && $input['name'] == 'u005')
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <div class="join">
                    <input type="text" class="input input-bordered join-item w-full" value="{{ $form_data[$input['name'] . 'a'] }}" disabled />
                    <input type="text" class="input input-bordered join-item w-full" value="{{ $form_data[$input['name'] . 'b'] }}" disabled />
                </div>
            </div>
            @elseif ($input['name'] == 'status-operasi')
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="{{ $form_data[$input['name']] ? 'Masih Beroperasi' : 'Tidak Beroperasi' }}" disabled />
            </div>
            @elseif ($input['name'] == 'sk-pengelola')
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="{{ $form_data[$input['name']] ? $form_data[$input['name']] : 'Tidak Ada' }}" disabled />
            </div>
            @elseif (in_array($input['label'], [
                "Nama Restoran",
                "Nama Jasa Boga/Katering",
                "Nama Rumah Makan",
                "Nama Sentra Pangan/Kantin",
                "Nama DAM",
                "Nama Gerai Pangan Jajanan",
                "Nama Gerai",
                "Nama Sekolah",
                "Nama Rumah Sakit",
                "Nama Puskesmas",
                "Nama Usaha",
                "Nama Sarana",

                "Alamat",
                "Nama Pengelola/Pemilik/Penanggung Jawab",
                "Jumlah Penjamah Pangan",
                "Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat",
                "Nomor Izin Usaha (Opsional)",
                "Nama Pemeriksa",
                "Instansi Pemeriksa",
                "Kontak Pengelola",
                "Titik GPS",

                "Jumlah Gerai",
                "Jumlah Penjamah Pangan/Operator DAM",
                "Lokasi/Tempat Sumber Air Baku",
                "Luas bangunan (m²)",
                "Nomor Induk Berusaha (Opsional)",
                "Lokasi Dapur Gerai Pangan",
                "Jenis makanan yang dijual",
                "Rute Berjualan",
                "Instansi/Wilayah Kerja IKL",
                "Kepala Sekolah/NIP",
                "Jumlah Siswa",
                "Jumlah Guru",
                "Nomor Pokok Sekolah Nasional",
                "Kontak Yang Dapat Dihubungi",
                "Jumlah Tempat Tidur",
                "Nama Penanggung Jawab Kesehatan Lingkungan Rumah Sakit",
                "Kontak Penanggung Jawab Kesehatan Lingkungan Rumah Sakit",
                "Pimpinan/PJ",
                "Jumlah Karyawan",
                "Nomor ID Puskesmas",
                "Kategori SAM",
                "Nama Instansi / Pemilik Sarana",
                "Tahun Konstruksi",
                "Nama Pemilik Sarana",
                "Kode Sarana",
                "Desa",
            ]))
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="{{ $form_data[$input['name']] }}" disabled />
            </div>
            @elseif (in_array($input['name'], ['slhs_issued_date', 'slhs_expire_date', 'sls_issued_date', 'sls_expire_date', 'tanggal-penilaian']))
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="{{ $form_data[$input['name']] ? $form_data[$input['name']] : 'Tidak Ada' }}" disabled />
            </div>
            @elseif ($form_data[$input['name']] == 1)
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="Iya" disabled />
            </div>
            @elseif ($form_data[$input['name']] == 0)
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="Tidak" disabled />
            </div>
            @elseif ($form_data[$input['name']] == -1)
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="Tidak Tahu" disabled />
            </div>
            @else
            <div class="input-group">
                <label>{{ $input['label'] }}</label>
                <input type="text" value="{{ $form_data[$input['name']] }}" disabled />
            </div>
            @endif
            @endforeach

        </div>
    </div>
</div>

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Skor Inspeksi</h1>
        <hr class="my-5" />
        <x-score :value="$form_data['skor']" :from="$inspection_name" :data="$form_data" />
    </div>
</div>

@if (isset($gerai_kantin))
<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-t-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Penilaian Gerai Kantin</h1>
    </div>
    <table class="table rounded-t-none rounded-b-xl border-t overflow-hidden bg-white">
        <thead class="bg-black/10">
            <tr>
                <th></th>
                <th>Nama Gerai</th>
                <th>Nama Pemilik Gerai</th>
                <th>Skor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gerai_kantin as $index => $gerai)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $gerai['subjek'] }}</td>
                <td>{{ $gerai['pengelola'] }}</td>
                <td class="font-bold">{{ $gerai['skor'] }}</td>
                <td class="flex gap-1.5">
                    @auth
                    @if (Auth::user()->role != "USER")
                    <a href="{{ route('gerai-kantin.edit', ['gerai_kantin' => $gerai['id']]) }}" class="btn btn-warning"><i class="ri-edit-fill"></i></a>
                    @endif
                    @if (Auth::user()->role == "SUPERADMIN")
                    <button type="button" class="btn btn-error btn-outline" onclick="showDeleteGeraiConfirmation('{{ $gerai['subjek'] }}', '{{ $gerai['id'] }}')"><i class="ri-delete-bin-6-line"></i></button>
                    @endif
                    @endauth
                    <a href="{{ route('gerai-kantin.index', ['export' => 'pdf', 'id' => $gerai['id']]) }}" class="btn btn-primary"><i class="ri-upload-2-line"></i></a>
                </td>
            </tr>
            @endforeach
            @if (count($gerai_kantin) == 0)
            <tr>
                <td colspan="7" class="text-center">Tidak ada Gerai Kantin yang dapat ditampilkan <a href="{{ route('gerai-kantin.create', ['kantin' => $form_data['id']]) }}" class="btn btn-sm btn-primary btn-outline ml-2"><span>Tambah Penilaian Gerai</span> <i class="ri-add-line"></i></a></td>
            </tr>
            @else
            <tr>
                <td colspan="6" class="text-center">
                    <a href="{{ route('gerai-kantin.create', ['kantin' => $form_data['id']]) }}" class="btn btn-sm btn-primary btn-outline"><span>Tambah Penilaian Gerai</span> <i class="ri-add-line"></i></a>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endif

@if ($inspection_name == 'Rumah Sakit')
<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Pelaporan Elektronik</h1>
        <hr class="my-5" />
        <p class="">{{ $form_data['pelaporan-elektronik'] }}</p>
    </div>
</div>

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Pengamanan Radiasi</h1>
        <hr class="my-5" />
        <p class="">{{ $form_data['pengamanan-radiasi'] }}</p>
    </div>
</div>

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Penyehatan Air Hemodiolisa</h1>
        <hr class="my-5" />
        <p class="">{{ $form_data['penyehatan-air-hemodiolisa'] }}</p>
    </div>
</div>
@endif

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Hasil IKL</h1>
        <hr class="my-5" />
        <p class="">{{ $form_data['catatan-lain'] ? $form_data['catatan-lain'] : 'Tidak ada ~' }}</p>
    </div>
</div>

<div class="px-3 pb-3 sm:px-6 sm:pb-6">
    <div class="bg-white rounded-xl p-6 sm:p-8">
        <h1 class="font-bold text-xl">Rencana Tindak Lanjut</h1>
        <hr class="my-5" />
        <p class="">{{ $form_data['rencana-tindak-lanjut'] ? $form_data['rencana-tindak-lanjut'] : 'Tidak ada ~' }}</p>
    </div>
</div>



<div class="px-3 pb-3 sm:px-6 sm:pb-6 grid grid-flow-row sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
    @auth
    @if (Auth::user()->role == "SUPERADMIN")
    <button type="button" class="btn btn-error btn-outline" onclick="showDeleteMainInspectionConfirmation()">HAPUS HASIL PENILAIAN</button>
    @endif
    @if (Auth::user()->role != "USER")
    <a href="{{ $edit_route }}" class="btn btn-warning">UBAH INFORMASI / PENILAIAN</a>
    @endif
    @endauth
    <a href="{{ $export_route }}" class="btn btn-primary">EXPORT HASIL (PDF) <i class="ri-upload-2-line"></i></a>
</div>

<x-modal.confirmation />

<script>
    let geraiDeleteBaseURL = `{{ route('gerai-kantin.destroy', ['gerai_kantin' => 0]) }}`;
    
    function showDeleteGeraiConfirmation(geraiName, geraiId) {
        showDeleteConfirmationModal(
            'Hapus Gerai',
            `Apakah Anda yakin ingin menghapus gerai "${geraiName}"? Data yang dihapus tidak dapat dikembalikan.`,
            function() {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = geraiDeleteBaseURL.substr(0, geraiDeleteBaseURL.length - 1) + geraiId;
                
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
    
    function showDeleteMainInspectionConfirmation() {
        showDeleteConfirmationModal(
            'Hapus Hasil Inspeksi',
            'Apakah Anda yakin ingin menghapus hasil inspeksi ini? Data yang dihapus tidak dapat dikembalikan.',
            function() {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ $destroy_route }}';
                
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
