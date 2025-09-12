@extends('layouts.app')

@section('content')
<x-section.page-header title="Inspeksi / Penilaian SAM Penyimpanan Air Hujan" />

<x-breadcrumb.create-inspection />

<form action="{{ route('penyimpanan-air-hujan.store') }}" method="POST">
    @csrf
    @method('POST')
    <!-- INFORMASI UMUM -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6">
        <div class="bg-white p-6 sm:p-8 rounded-xl">
            <h1 class="font-bold text-xl">Informasi Umum</h1>
            <hr class="my-5" />
            <div class="grid grid-flow-row md:grid-cols-2 gap-5">
                @foreach ($informasi_umum as $form_input)

                @switch($form_input['name'])
                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.create />
                @break

                @case('kecamatan')
                <div class="input-group">
                    <label for="kec">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kec" class="select select-bordered" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                @break

                @case('kelurahan')
                <div class="input-group">
                    <label for="kel">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kel" class="select select-bordered" required>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                </div>
                @break

                @case('status-operasi')
                <div class="input-group">
                    <label for="status-operasi">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="status-operasi" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1">Masih Beroperasi</option>
                        <option value="0">Tidak Beroperasi</option>
                    </select>
                </div>
                @break
                @case('dokumen_slhs')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="url" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="https://drive.google.com/... atau link dokumen lainnya" class="input input-bordered" />
                    <div class="text-sm text-gray-500 mt-1">Masukkan link dokumen SLHS (Google Drive, OneDrive, atau penyimpanan cloud lainnya)</div>
                </div>
                @break

                @case('slhs_issued_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" onchange="calculateSlhsExpireDate()" class="input input-bordered" />
                </div>
                @break

                @case('slhs_expire_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }} <span class="text-xs text-gray-500">(Auto dari Issued Date + 3 tahun)</span></label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" readonly class="input input-bordered bg-gray-100" />
                </div>
                @break


                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" class="join-item w-full" placeholder="-6.324667, 106.891268" required />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" required />
                </div>
                @endswitch
                @endforeach
            </div>
        </div>
    </div>

    <!-- FORM PENILAIAN -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6 flex flex-wrap lg:flex-nowrap gap-5">
        <div>

            <div class="bg-white flex-grow pb-4 sm:pb-8">
                <div class="p-6 sm:p-8">
                    <h1 class="font-bold text-xl">Data Khusus Penilaian Resiko</h1>
                </div>

                @foreach ($data_kpr as $index => $form_input)
                <div class="px-3 sm:px-8">
                    <div class="p-4 border rounded mb-3">
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                            <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                            <span class="badge badge-outline badge-primary">+{{ $form_input['option'][1]['value'] }}</span>
                        </div>
                        <hr class="mt-3 mb-2" />
                        <div class="flex gap-5">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="{{ $form_input['option'][0]['value'] }}" checked />
                                    <span class="label-text">Tidak</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['option'][1]['value'] }}" />
                                    <span class="label-text">Ya</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div id="catatan-lain" class="px-6 sm:px-8 pt-2">
                    <label for="catatan-lain" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Hasil IKL</label>
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <textarea name="catatan-lain" id="catatan-lain" class="resize-none h-52" required placeholder="Catatan mengenai hasil inpeksi lingkungan kesehatan..."></textarea>
                    </div>
                </div>

                <div id="rencana-tindak-lanjut" class="px-6 sm:px-8 pt-2">
                    <label for="rencana-tindak-lanjut" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Rencana Tindak Lanjut</label>
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <textarea name="rencana-tindak-lanjut" id="rencana-tindak-lanjut" class="resize-none h-52" required placeholder="Rencana untuk tindak lanjut kedepannya setelah inpeksi lingkungan kesehatan..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky top-5 h-fit min-w-72 w-full lg:w-fit">
            <button onclick="reminder_before_submit.showModal()" type="button" class="btn btn-primary btn-block">SUBMIT PENILAIAN</button>

            <x-modal.reminder-before-submit />
        </div>
    </div>
</form>

<x-modal.get-lat-long />
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>

<script>
    function calculateSlhsExpireDate() {
        const issuedDateInput = document.getElementById('slhs_issued_date');
        const expireDateInput = document.getElementById('slhs_expire_date');
        
        if (issuedDateInput && expireDateInput && issuedDateInput.value) {
            // Parse issued date
            const issuedDate = new Date(issuedDateInput.value);
            
            // Add 3 years
            const expireDate = new Date(issuedDate);
            expireDate.setFullYear(expireDate.getFullYear() + 3);
            
            // Format to YYYY-MM-DD
            const formattedDate = expireDate.toISOString().split('T')[0];
            
            // Set expire date
            expireDateInput.value = formattedDate;
        } else if (issuedDateInput && expireDateInput && !issuedDateInput.value) {
            // Clear expire date if issued date is cleared
            expireDateInput.value = '';
        }
    }

    // Initialize on page load if values exist
    document.addEventListener('DOMContentLoaded', function() {
        calculateSlhsExpireDate();
    });

    $('input[name="ada-bangunan-penangkap"]').on('change', function() {
        const value = $('input[name="ada-bangunan-penangkap"]:checked').val();
        if (value == '1') {
            $('.extra-question').removeClass('hidden');
        } else {
            $('.extra-question').addClass('hidden');
        }
    })
</script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
