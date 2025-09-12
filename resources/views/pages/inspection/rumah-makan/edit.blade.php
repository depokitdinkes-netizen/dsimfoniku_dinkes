@extends('layouts.app')

@section('content')
<x-section.page-header title="Ubah Informasi / Penilaian Rumah Makan Tipe {{ ucfirst(strtolower($form_data['u009'])) }}" />

<x-breadcrumb.edit-inspection showRoute="{{ route('rumah-makan.show', ['rumah_makan' => $form_data['id']]) }}" />

<form action="{{ route('rumah-makan.update', ['rumah_makan' => $form_data['id']]) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- INFORMASI UMUM -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6">
        <div class="bg-white p-6 sm:p-8 rounded-xl">
            <h1 class="font-bold text-xl">Informasi Umum</h1>
            <hr class="my-5" />
            <div class="grid grid-flow-row md:grid-cols-2 gap-5">
                @foreach ($informasi_umum as $form_input)

                @switch($form_input['name'])
                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.edit :data="$form_data['instansi-pemeriksa']" />
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

                @case('u006')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data['u006'] }}" />
                </div>
                @break

                @case('u009')
                <div class="input-group">
                    <label for="u009">{{ $form_input['label'] }}</label>
                    <input type="text" value="{{ ucfirst(strtolower($form_data['u009'])) }}" disabled />

                    <input type="hidden" id="u009" name="u009" value="{{ $form_data['u009'] }}" />
                </div>
                @break

                @case('status-operasi')
                <div class="input-group">
                    <label for="status-operasi">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="status-operasi" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option @if($form_data['status-operasi']) selected @endif value="1">Masih Beroperasi</option>
                        <option @if(!$form_data['status-operasi']) selected @endif value="0">Tidak Beroperasi</option>
                    </select>
                </div>
                @break

                @case('dokumen_slhs')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    @if($form_data['dokumen_slhs'])
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Link saat ini: </span>
                        <a href="{{ $form_data['dokumen_slhs'] }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $form_data['dokumen_slhs'] }}
                        </a>
                    </div>
                    @endif
                    <input type="url" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" placeholder="https://drive.google.com/... atau link dokumen lainnya" class="input input-bordered" />
                    <div class="text-sm text-gray-500 mt-1">Masukkan link dokumen SLHS (Google Drive, OneDrive, atau penyimpanan cloud lainnya)</div>
                </div>
                @break

                @case('slhs_issued_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" onchange="calculateSlhsExpireDate()" />
                </div>
                @break

                @case('slhs_expire_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }} <span class="text-xs text-gray-500">(Auto dari Issued Date + 3 tahun)</span></label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" readonly class="input input-bordered bg-gray-100" />
                </div>
                @break

                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" class="join-item w-full" placeholder="-6.324667, 106.891268" required value="{{ $form_data['koordinat'] }}" required />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" required />
                </div>
                @endswitch

                @endforeach
            </div>
        </div>
    </div>

    <!-- FORM PENILAIAN -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6 flex flex-wrap lg:flex-nowrap gap-5">
        <div class="bg-white flex-grow pb-4 rounded-xl">
            <div class="p-6 sm:p-8">
                <h1 class="font-bold text-xl">Formulir Penilaian</h1>
            </div>

            @foreach ($form_penilaian as $index => $form_input)
            @switch($form_input['type'])

            @case('h2')
            <div class="text-white bg-black/40 px-6 sm:px-8 py-4 mb-6 @if ($index > 0) mt-10 @endif">
                <h2 class="font-semibold text-lg relative">{{ $form_input['label'] }}</h2>
            </div>
            @break

            @case('h3')
            <div id="{{ $form_input['id'] }}" class="px-6 sm:px-8 pt-2">
                <h3 class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">{{ $form_input['label'] }}</h3>
            </div>
            @break

            @case('h4')
            <div class="px-6 sm:px-8 pb-3 mt-4">
                <h4 class="text-base underline underline-offset-8">{{ $form_input['label'] }} :</h4>
            </div>
            @break

            @case('select')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="flex gap-1 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <span class="badge badge-outline badge-error ml-auto">+{{ $form_input['option'][1]['value'] }}</span>
                    </div>
                    <hr class="mt-3 mb-2" />
                    <div class="flex gap-5">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['option'][0]['value'] }}" @if($form_data[$form_input['name']]==0) checked @endif />
                                <span class="label-text">{{ $form_input['option'][0]['label'] }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="{{ $form_input['option'][1]['value'] }}" @if(0 < $form_data[$form_input['name']]) checked @endif />
                                <span class="label-text">{{ $form_input['option'][1]['label'] }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @break

            @default

            @endswitch
            @endforeach

            <div id="catatan-lain" class="px-6 sm:px-8 pt-2">
                <label for="catatan-lain" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Hasil IKL</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="catatan-lain" id="catatan-lain" class="resize-none h-52" required placeholder="Catatan mengenai hasil inpeksi lingkungan kesehatan...">{{ $form_data['catatan-lain'] }}</textarea>
                </div>
            </div>

            <div id="rencana-tindak-lanjut" class="px-6 sm:px-8 pt-2">
                <label for="rencana-tindak-lanjut" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Rencana Tindak Lanjut</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="rencana-tindak-lanjut" id="rencana-tindak-lanjut" class="resize-none h-52" required placeholder="Rencana untuk tindak lanjut kedepannya setelah inpeksi lingkungan kesehatan...">{{ $form_data['rencana-tindak-lanjut'] }}</textarea>
                </div>
            </div>
        </div>

        <div class="sticky top-5 h-fit min-w-72 w-full lg:w-fit">
            <div class="bg-white p-6 max-h-[30rem] overflow-y-auto hidden lg:block mb-5 rounded-xl">
                @foreach ($form_penilaian as $index => $heading)

                @switch($heading['type'])
                @case('h2')
                <p class="font-semibold text-sm @if ($index > 0) mt-5 @endif">{{ $heading['label'] }}</p>
                @break
                @case('h3')
                <a href="#{{ $heading['id'] }}" class="text-blue-500 text-sm my-2 block ml-2 underline ">{{ $heading['label'] }}</a>
                @break
                @endswitch

                @endforeach

                <a href="#catatan-lain" class="text-blue-500 text-sm my-2 block ml-2 underline">Hasil IKL</a>
                <a href="#rencana-tindak-lanjut" class="text-blue-500 text-sm my-2 block ml-2 underline">Rencana Tindak Lanjut</a>
            </div>
            <button class="btn btn-primary btn-block" name="action" value="update">SIMPAN PENILAIAN</button>
            <button type="submit" class="btn btn-info btn-outline btn-block mt-5" name="action" value="duplicate">DUPLIKAT PENILAIAN</button>
        </div>

    </div>
</form>

<x-modal.get-lat-long />
<x-modal.confirmation />

<script src="{{ asset('js/fallbackData.js') }}"></script>
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script>
    $(document).ready(function() {
        let kecVal = "{{ $form_data['kecamatan'] }}";
        let kelVal = "{{ $form_data['kelurahan'] }}";

        // Wait for kecamatan data to load
        let checkKec = setInterval(function() {
            if (kecamatan.length > 0) {
                // Populate kecamatan dropdown
                let kecOptions = '<option value="">Pilih Kecamatan</option>';
                kecamatan.forEach((el) => {
                    kecOptions += `<option value="${el.name}" ${kecVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                });
                $("#kec").html(kecOptions);
                $("#kec").prop('disabled', false);

                // If there's a selected kecamatan, load its kelurahan
                if (kecVal) {
                    let selectedKec = kecamatan.find((el) => el.name == kecVal);
                    if (selectedKec) {
                        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
                            .then((response) => response.json())
                            .then((villages) => {
                                let kelOptions = '<option value="">Pilih Kelurahan</option>';
                                villages.forEach((el) => {
                                    kelOptions += `<option value="${el.name}" ${kelVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                                });
                                $("#kel").html(kelOptions);
                                $("#kel").prop('disabled', false);
                            })
                            .catch((error) => {
                                console.error('Error loading kelurahan:', error);
                                // Try fallback data
                                if (window.FALLBACK_KELURAHAN && window.FALLBACK_KELURAHAN[selectedKec.id]) {
                                    let fallbackVillages = window.FALLBACK_KELURAHAN[selectedKec.id];
                                    let kelOptions = '<option value="">Pilih Kelurahan</option>';
                                    fallbackVillages.forEach((el) => {
                                        kelOptions += `<option value="${el.name}" ${kelVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                                    });
                                    $("#kel").html(kelOptions);
                                    $("#kel").prop('disabled', false);
                                }
                            });
                    }
                }

                clearInterval(checkKec);
            }
        }, 500);
    });

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
