@extends('layouts.app')

@section('content')
<x-section.page-header title="Inspeksi / Penilaian Rumah Sakit" />

<x-breadcrumb.create-inspection />

<form action="{{ route('rumah-sakit.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <!-- INFORMASI UMUM -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6">
        <div class="bg-white p-6 sm:p-8 rounded-xl">
            <h1 class="font-bold text-        if (!$('input[name="pengisian-dsmiling"]:checked').length) {
            $('input[name="pengisian-dsmiling"][value="Ya"]').prop('checked', true);
        }
    });
    
    // Handle option selection for fields with duplicate values
    $('input[name="1002"], input[name="f9003"]').on('change', function() {
        const fieldName = $(this).attr('name');
        const selectedValue = $(this).val();
        
        // Find the corresponding hidden field with option ID for the selected radio button
        const parentLabel = $(this).closest('label');
        const hiddenField = parentLabel.find('input[type="hidden"][name^="' + fieldName + '_selected_id_"]');
        
        if (hiddenField.length) {
            // Remove any existing selected_id field and add the new one
            $('input[name="' + fieldName + '_selected_id"]').remove();
            $('<input>').attr({
                type: 'hidden',
                name: fieldName + '_selected_id',
                value: hiddenField.val()
            }).appendTo('form');
        }
    });

    // Initialize default selected IDs on page load
    $('input[name="1002"]:checked, input[name="f9003"]:checked').each(function() {
        const fieldName = $(this).attr('name');
        const parentLabel = $(this).closest('label');
        const hiddenField = parentLabel.find('input[type="hidden"][name^="' + fieldName + '_selected_id_"]');
        
        if (hiddenField.length) {
            $('input[name="' + fieldName + '_selected_id"]').remove();
            $('<input>').attr({
                type: 'hidden',
                name: fieldName + '_selected_id',
                value: hiddenField.val()
            }).appendTo('form');
        }
    });
});Informasi Umum</h1>
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

                @case('kelas')
                <div class="input-group">
                    <label for="kelas">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kelas" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih Kelas</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                @break

                @case('dokumen-rintek-tps-b3')
                <div class="input-group">
                    <label>{{ $form_input['label'] }}</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Tidak" checked />
                            <span>Tidak</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Ya" />
                            <span>Ya</span>
                        </label>
                    </div>
                </div>
                @break

                @case('dokumen-pertek-ipal')
                <div class="input-group">
                    <label>{{ $form_input['label'] }}</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Tidak" checked />
                            <span>Tidak</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Ya" />
                            <span>Ya</span>
                        </label>
                    </div>
                </div>
                @break

                @case('pengisian-sikelim')
                <div class="input-group">
                    <label>{{ $form_input['label'] }}</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Tidak" />
                            <span>Tidak</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Ya" checked />
                            <span>Ya</span>
                        </label>
                    </div>
                </div>
                @break

                @case('pengisian-dsmiling')
                <div class="input-group">
                    <label>{{ $form_input['label'] }}</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Tidak" />
                            <span>Tidak</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="Ya" checked />
                            <span>Ya</span>
                        </label>
                    </div>
                </div>
                @break

                @case('nomor-dokumen-rintek-tps-b3')
                <div class="input-group" id="nomor-dokumen-rintek-tps-b3-group" style="display: none;">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="text" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="Masukkan nomor dokumen..." />
                </div>
                @break

                @case('nomor-dokumen-pertek-ipal')
                <div class="input-group" id="nomor-dokumen-pertek-ipal-group" style="display: none;">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="text" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="Masukkan nomor dokumen..." />
                </div>
                @break

                @case('alasan-sikelim')
                <div class="input-group" id="alasan-sikelim-group" style="display: none;">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="text" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="Masukkan alasan..." />
                </div>
                @break

                @case('alasan-dsmiling')
                <div class="input-group" id="alasan-dsmiling-group" style="display: none;">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="text" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="Masukkan alasan..." />
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
            <div id="{{ strtolower(str_replace(' ', '-', $form_input['label'])) }}" class="px-6 sm:px-8 pt-2">
                <h3 class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">{{ $form_input['label'] }}</h3>
            </div>
            @break

            @case('h4')
            <div class="px-6 sm:px-8 pb-3 mt-4">
                <h4 class="text-base underline underline-offset-8">{{ $form_input['label'] }} :</h4>
            </div>
            @break

            @case('selectc')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <div>
                            <span class="badge badge-outline badge-ghost">×{{ $form_input['bobot'] }}</span>
                            <span class="badge badge-outline badge-error">+{{ $form_input['sesuai'] }}</span>
                        </div>
                    </div>
                    <hr class="mt-3 mb-2" />
                    <div class="flex gap-5">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['option'][0]['value'] }}" checked />
                                <span class="label-text">{{ $form_input['option'][0]['label'] }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="{{ $form_input['option'][1]['value'] }}" />
                                <span class="label-text">{{ $form_input['option'][1]['label'] }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @break

            @case('selects')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <div>
                            <span class="badge badge-outline badge-ghost">×{{ $form_input['bobot'] }}</span>
                        </div>
                    </div>
                    <hr class="mt-3 mb-2" />
                    @foreach($form_input['options'] as $option)
                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-2 flex-wrap">
                            @php
                                // For fields with bobot = 0, use original option value
                                $actualValue = $form_input['bobot'] == 0 ? $option['value'] : $form_input['bobot'] * $option['value'];
                                // For create form, select default based on highest value or specific defaults
                                $isDefaultSelected = false;
                                
                                // Special default handling for specific fields
                                if ($form_input['name'] == '1002' && isset($option['id']) && $option['id'] == '1002_a') {
                                    $isDefaultSelected = true; // First option for 1002
                                } elseif ($form_input['name'] == 'f9003' && isset($option['id']) && $option['id'] == 'f9003_a') {
                    $isDefaultSelected = true; // First option for f9003
                } elseif (in_array($form_input['name'], ['f1001', 'f1003', 'f1004', 'f2002', 'f3001', 'f3002', 'f4001', 'f4002', 'f4005', 'f5002', 'f7001', 'f7002', 'f7003', 'f7004', 'f7005', 'f9002']) && $loop->first) {
                    $isDefaultSelected = true; // First option for problematic selects fields
                } elseif (!in_array($form_input['name'], ['1002', 'f9003', 'f1001', 'f1003', 'f1004', 'f2002', 'f3001', 'f3002', 'f4001', 'f4002', 'f4005', 'f5002', 'f7001', 'f7002', 'f7003', 'f7004', 'f7005', 'f9002']) && $loop->first) {
                                    $isDefaultSelected = true; // First option for all other selects fields
                                }
                            @endphp
                            <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary flex-shrink-0" value="{{ $actualValue }}" @if($isDefaultSelected) checked @endif />
                            <span class="label-text flex-1 mr-2">{{ $option['label'] }}</span>
                            <span class="badge badge-outline badge-error flex-shrink-0">+{{ $option['value'] }}</span>
                            
                            @if(isset($option['id']) && in_array($form_input['name'], ['1002', 'f9003']))
                                <input type="hidden" name="{{ $form_input['name'] }}_selected_id_{{ $loop->index }}" value="{{ $option['id'] }}" />
                            @endif
                        </label>
                    </div>
                    @endforeach
                    
                    {{-- Input field untuk pilihan 'Lainnya...' --}}
                    @if(in_array($form_input['name'], ['f1006', 'f1007', '6001e']))
                        <div class="mt-3" id="{{ $form_input['name'] }}_lainnya_container" style="display: none;">
                            <input type="text" name="{{ $form_input['name'] }}_lainnya" class="input input-bordered w-full" placeholder="Sebutkan..." />
                        </div>
                    @endif
                </div>
            </div>
            @break

            @case('input')
            @case('date')
            @case('text')
            @case('number')
            @case('email')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="input-group">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" placeholder="{{ $form_input['label'] }}" />
                    </div>
                </div>
            </div>
            @break

            @case('textarea')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="input-group">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <textarea id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" class="resize-none h-32" placeholder="{{ $form_input['label'] }}"></textarea>
                    </div>
                </div>
            </div>
            @break

            @default

            @endswitch
            @endforeach

            <!-- Informasi Tambahan Section -->
            <div class="px-6 sm:px-8 pt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-gray-200 pb-2">Informasi Tambahan</h2>
            </div>

            <div id="pelaporan-elektronik" class="px-6 sm:px-8 pt-2">
                <label for="pelaporan-elektronik" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Pelaporan Elektronik</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="pelaporan-elektronik" id="pelaporan-elektronik" class="resize-none h-52" required placeholder="Catatan mengenai elektronik (jika kosong isi &quot;-&quot;)" required></textarea>
                </div>
            </div>

            <div id="pengamanan-radiasi" class="px-6 sm:px-8 pt-2">
                <label for="pengamanan-radiasi" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Pengamanan Radiasi</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="pengamanan-radiasi" id="pengamanan-radiasi" class="resize-none h-52" required placeholder="Catatan mengenai pengamanan radiasi (jika kosong isi &quot;-&quot;)" required></textarea>
                </div>
            </div>

            <div id="penyehatan-air-hemodiolisa" class="px-6 sm:px-8 pt-2">
                <label for="penyehatan-air-hemodiolisa" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Penyehatan Air Hemodiolisa</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="penyehatan-air-hemodiolisa" id="penyehatan-air-hemodiolisa" class="resize-none h-52" required placeholder="Catatan mengenai penyehatan air hemodiolisa (jika kosong isi &quot;-&quot;)" required></textarea>
                </div>
            </div>

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

        <div class="sticky top-5 h-fit min-w-72 w-full lg:w-fit">
            <div class="bg-white p-6 max-h-[30rem] overflow-y-auto hidden lg:block mb-5 rounded-xl">
                @foreach ($form_penilaian as $index => $heading)

                @switch($heading['type'])
                @case('h2')
                <p class="font-semibold text-sm @if ($index > 0)
                mt-5
            @endif">{{ $heading['label'] }}</p>
                @break
                @case('h3')
                <a href="#{{ strtolower(str_replace(' ', '-', $heading['label'])) }}" class="text-blue-500 text-sm my-2 block ml-2 underline ">{{ $heading['label'] }}</a>
                @break
                @endswitch

                @endforeach
                <a href="#pelaporan-elektronik" class="text-blue-500 text-sm my-2 block ml-2 underline">Pelaporan Elektronik</a>
                <a href="#pengamanan-radiasi" class="text-blue-500 text-sm my-2 block ml-2 underline">Pengamanan Radiasi</a>
                <a href="#penyehatan-air-hemodiolisa" class="text-blue-500 text-sm my-2 block ml-2 underline">Penyehatan Air Hemodiolisa</a>
                <a href="#catatan-lain" class="text-blue-500 text-sm my-2 block ml-2 underline">Hasil IKL</a>
                <a href="#rencana-tindak-lanjut" class="text-blue-500 text-sm my-2 block ml-2 underline">Rencana Tindak Lanjut</a>
            </div>
            <button onclick="reminder_before_submit.showModal()" type="button" class="btn btn-primary btn-block">SUBMIT PENILAIAN</button>

            <x-modal.reminder-before-submit />
        </div>

    </div>
</form>

<x-modal.get-lat-long />

<script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/inspection/rumah-sakit/create.js') }}"></script>
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
