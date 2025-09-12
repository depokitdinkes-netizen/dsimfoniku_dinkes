<dialog id="filter_history" class="modal">
    <form method="GET" action="{{ route('history') }}" class="modal-box max-w-[35rem]">

        <h3 class="font-bold text-lg">Filter Histori Hasil Inspeksi</h3>

        <div class="mt-6 grid grid-flow-row gap-3">
            <div class="input-group">
                <label for="my">Bulan & Tahun</label>
                <input type="month" name="my" id="my" class="input input-bordered join-item" value="{{ request('my') }}" />
            </div>
            <div>
                <p class="mb-2 text-sm font-medium">Tipe Form</p>
                <div class="grid grid-flow-row sm:grid-cols-2 gap-2">
                    @foreach([
                    'akomodasi' =>              'Akomodasi',
                    'akomodasi-lain' =>         'Akomodasi Lainnya',
                    'depot-air-minum' =>        'Depot Air Minum',
                    'tempat-olahraga' =>        'Gelanggang Olahraga',
                    'gerai-pangan-jajanan' =>   'Gerai Pangan Jajanan',
                    'gerai-jajanan-keliling' => 'Gerai Pangan Jajanan Keliling',
                    'jasa-boga-katering' =>     'Jasa Boga/Katering',
                    'renang-pemandian' =>       'Kolam Renang',
                    'pasar' =>                  'Pasar',
                    'pasar-internal' =>         'Pasar Internal',
                    'puskesmas' =>              'Puskesmas',
                    'restoran' =>               'Restoran',
                    'rumah-makan' =>            'Rumah Makan',
                    'rumah-sakit' =>            'Rumah Sakit',
                    'penyimpanan-air-hujan' =>  'SAM Penyimpanan Air Hujan',
                    'perlindungan-mata-air' =>  'SAM Perlindungan Mata Air',
                    'perpipaan-non-pdam' =>     'SAM Perpipaan Non PDAM',
                    'perpipaan' =>              'SAM Perpipaan PDAM',
                    'sumur-bor-pompa' =>        'SAM Sumur Bor dengan Pompa Tangan',
                    'sumur-gali' =>             'SAM Sumur Gali dengan Kerekan',
                    'sekolah' =>                'Sekolah',
                    'kantin' =>                 'Sentra Kantin',
                    'stasiun' =>                'Stasiun',
                    'tempat-rekreasi' =>        'Tempat Rekreasi',
                    ] as $key => $label)
                    <label for="{{ $key }}" class="flex items-center gap-2 cursor-pointer text-sm p-2 border rounded-md">
                        <input type="checkbox" name="ft[]" value="{{ $key }}" id="{{ $key }}" class="checkbox checkbox-primary rounded form-type-checkbox" @if(in_array($key, request('ft', []))) checked @endif>
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <!-- Filter Status SLHS -->
            <div class="input-group">
                <label for="slhs_status">Status SLHS</label>
                <select name="slhs_status" id="slhs_status" class="select select-bordered">
                    <option value="">Semua Status SLHS</option>
                    <option value="excellent" @if(request('slhs_status') == 'excellent') selected @endif>Excellent (3+ tahun)</option>
                    <option value="good" @if(request('slhs_status') == 'good') selected @endif>Good (2+ tahun)</option>
                    <option value="caution" @if(request('slhs_status') == 'caution') selected @endif>Caution (6-12 bulan)</option>
                    <option value="warning" @if(request('slhs_status') == 'warning') selected @endif>Warning (< 6 bulan)</option>
                    <option value="critical" @if(request('slhs_status') == 'critical') selected @endif>Critical (< 1 bulan)</option>
                    <option value="expired" @if(request('slhs_status') == 'expired') selected @endif>Expired</option>
                    <option value="no-data" @if(request('slhs_status') == 'no-data') selected @endif>Tidak ada data</option>
                </select>
            </div>
            
            <!-- Filter Jenis Sekolah - hanya muncul jika sekolah dipilih -->
            <div class="input-group" id="jenis-sekolah-filter" style="display: none;">
                <label for="jenis_sekolah">Jenis Sekolah</label>
                <select name="jenis_sekolah" id="jenis_sekolah" class="select select-bordered">
                    <option value="">Semua Jenis Sekolah</option>
                    <option value="TK" @if(request('jenis_sekolah') == 'TK') selected @endif>TK (Taman Kanak-Kanak)</option>
                    <option value="PAUD" @if(request('jenis_sekolah') == 'PAUD') selected @endif>PAUD (Pendidikan Anak Usia Dini)</option>
                    <option value="SD" @if(request('jenis_sekolah') == 'SD') selected @endif>SD (Sekolah Dasar)</option>
                    <option value="MI" @if(request('jenis_sekolah') == 'MI') selected @endif>MI (Madrasah Ibtidaiyah)</option>
                    <option value="SMP" @if(request('jenis_sekolah') == 'SMP') selected @endif>SMP (Sekolah Menengah Pertama)</option>
                    <option value="MTs" @if(request('jenis_sekolah') == 'MTs') selected @endif>MTs (Madrasah Tsanawiyah)</option>
                    <option value="SMA" @if(request('jenis_sekolah') == 'SMA') selected @endif>SMA (Sekolah Menengah Atas)</option>
                    <option value="MA" @if(request('jenis_sekolah') == 'MA') selected @endif>MA (Madrasah Aliyah)</option>
                    <option value="SMK" @if(request('jenis_sekolah') == 'SMK') selected @endif>SMK (Sekolah Menengah Kejuruan)</option>
                </select>
            </div>
            <div class="input-group">
                <label for="kec">Kecamatan</label>
                <select name="kec" id="kec" class="select select-bordered">
                    <option value="" selected>Pilih Kecamatan</option>
                </select>
            </div>
            <div class="input-group" id="kel"></div>
        </div>

        <div class="flex justify-end gap-1.5 mt-6">
            <a href="{{ route('history') }}" class="btn btn-outline flex-1">RESET FILTER</a>
            <button type="submit" class="btn btn-primary flex-1">SET FILTER</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script src="{{ asset('js/getDistrictsAndVillagesCheckbox.js') }}"></script>

<script>
function toggleSekolahFilter() {
    const sekolahCheckbox = document.getElementById('sekolah');
    const jenisSekolahFilter = document.getElementById('jenis-sekolah-filter');
    const jenisSekolahSelect = document.getElementById('jenis_sekolah');
    
    if (sekolahCheckbox && jenisSekolahFilter) {
        if (sekolahCheckbox.checked) {
            jenisSekolahFilter.style.display = 'block';
        } else {
            jenisSekolahFilter.style.display = 'none';
            if (jenisSekolahSelect) {
                jenisSekolahSelect.value = ''; // Reset pilihan jenis sekolah
            }
        }
    }
}

// Cek status saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah sekolah sudah dipilih dari request sebelumnya
    const sekolahCheckbox = document.getElementById('sekolah');
    const currentFormTypes = @json(request('ft', []));
    
    if (sekolahCheckbox && currentFormTypes.includes('sekolah')) {
        sekolahCheckbox.checked = true;
    }
    
    toggleSekolahFilter();
    
    // Jika ada parameter jenis_sekolah di URL, tampilkan filter
    const jenisSekolahParam = @json(request('jenis_sekolah'));
    if (jenisSekolahParam && sekolahCheckbox) {
        sekolahCheckbox.checked = true;
        toggleSekolahFilter();
    }
    
    // Tambahkan event listener untuk semua checkbox form type
    const formTypeCheckboxes = document.querySelectorAll('.form-type-checkbox');
    formTypeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleSekolahFilter);
    });
});
</script>
