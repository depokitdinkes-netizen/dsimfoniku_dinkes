@extends('layouts.app')

@section('content')
<x-section.page-header title="Inspeksi / Penilaian SAM Perpipaan Non PDAM" />

<x-breadcrumb.create-inspection />

<form action="{{ route('perpipaan-non-pdam.store') }}" method="POST">
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

                @case('u001')
                <div class="input-group">
                    <label for="u001">{{ $form_input['label'] }}</label>
                    <select id="u001-select" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih</option>
                        <option value="Pasar">Pasar</option>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Masyarakat">Masyarakat</option>
                        <option value="Tempat Pengolahan Pangan">Tempat Pengolahan Pangan</option>
                        <option value="Other">Yang Lain</option>
                    </select>
                    <input type="text" id="u001" name="u001" placeholder="Ketikan Kategori" class="hidden" required />

                    <script>
                        $(document).ready(function() {
                            $('#u001-select').change(function() {
                                $('#u001').val(this.value);

                                if (this.value == 'Other') {
                                    $('#u001').removeClass('hidden');
                                    $('#u001').prop('required', true)
                                    $('#u001').val('');
                                } else {
                                    $('#u001').addClass('hidden');
                                    $('#u001').removeAttr('required');
                                }
                            })
                        })
                    </script>
                </div>
                @break

                @case('sk-pengelola')
                <div class="input-group">
                    <label for="sk-pengelola-select">{{ $form_input['label'] }}</label>
                    <select id="sk-pengelola-select" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih</option>
                        <option value="1">Ada</option>
                        <option value="-1">Tidak Ada</option>
                    </select>
                    <input type="number" id="sk-pengelola" name="sk-pengelola" placeholder="Ketikan SK" class="hidden" />

                    <script>
                        $(document).ready(function() {
                            $('#sk-pengelola-select').change(function() {
                                if (this.value == 1) {
                                    $('#sk-pengelola').removeClass('hidden');
                                    $('#sk-pengelola').prop('required', true)
                                } else {
                                    $('#sk-pengelola').addClass('hidden');
                                    $('#sk-pengelola').removeAttr('required');
                                    $('#sk-pengelola').val('');
                                }
                            })
                        })
                    </script>
                </div>
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
            <div class="bg-white flex-grow pb-4 sm:pb-8 mb-6 rounded-xl">
                <div class="p-6 sm:p-8">
                    <h1 class="font-bold text-xl">Kualitas Fisik Air</h1>
                </div>

                @foreach ($kualitas_fa as $form_input)
                @if($form_input['type'] == 'checkbox')
                <div class="px-3 sm:px-8">
                    <div class="p-4 border rounded mb-3">
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                            <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        </div>
                        <hr class="mt-3 mb-2" />
                        <div class="flex gap-5">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="1" checked />
                                    <span class="label-text">Sesuai</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="0" />
                                    <span class="label-text">Tidak Sesuai</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                <div class="px-6 sm:px-8">
                    <hr class="my-6" />
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <label for="kfa005">{{ $kualitas_fa[4]['label'] }}</label>
                        <select name="kfa005" id="kfa005" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Pilih</option>
                            <option value="0">Tidak digunakan untuk air minum, hanya untuk MCK
                            </option>
                            <option value="1">Ya, dengan cara direbus terlebih dahulu dan digunakan untuk masak
                            </option>
                            <option value="2">Ya, dan langsung diminum dari keran
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-white flex-grow pb-4 sm:pb-8">
                <div class="p-6 sm:p-8">
                    <h1 class="font-bold text-xl">Data Khusus Penilaian Resiko</h1>
                </div>

                @foreach ($data_kpr as $index => $form_input)
                @switch($form_input['type'])

                @case('h2')
                <div id="{{ $form_input['id'] }}" class="text-white bg-black/40 px-8 py-4 mb-6 @if ($index > 0) mt-10 @endif">
                    <h2 class="font-semibold text-lg relative">{{ $form_input['label'] }}</h2>
                </div>

                @break

                @case('select')
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
                @break

                @endswitch
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
        
        if (issuedDateInput && issuedDateInput.value && expireDateInput) {
            const issuedDate = new Date(issuedDateInput.value);
            // Add 3 years to the issued date
             issuedDate.setFullYear(issuedDate.getFullYear() + 3);
            
            // Format the date as YYYY-MM-DD
            const year = issuedDate.getFullYear();
            const month = String(issuedDate.getMonth() + 1).padStart(2, '0');
            const day = String(issuedDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            expireDateInput.value = formattedDate;
        } else if (expireDateInput) {
            // Clear expire date if no issued date
            expireDateInput.value = '';
        }
    }

    // Auto-calculate on page load if issued date already filled
    calculateSlhsExpireDate();
</script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
