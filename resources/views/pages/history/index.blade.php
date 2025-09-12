@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">Histori Hasil Inspeksi</h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>

<div class="p-3 sm:p-6">
    <div class="flex justify-end gap-3 flex-wrap mb-6">
        <button class="btn btn-primary btn-outline" onclick="filter_history.showModal()">
            <i class="ri-equalizer-3-fill"></i>
            <span>FILTER</span>
            @if(request()->hasAny(['my', 'ft', 'kec', 'kel', 'jenis_sekolah', 'slhs_status']))
                <span class="badge badge-accent badge-sm ml-1">ON</span>
            @endif
        </button>
        <form method="GET" class="join">
            <!-- Preserve all current query parameters except s -->
            @foreach (request()->query() as $key => $value)
                @if ($key !== 's')
                    @if (is_array($value))
                        @foreach ($value as $subValue)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endif
            @endforeach
            
            <button type="submit" class="btn btn-primary btn-square join-item">
                <i class="ri-search-line"></i>
            </button>
            <input type="text" name="s" class="input input-bordered join-item w-full" placeholder="Cari berdasarkan nama tempat..." value="{{ request('s') }}" />
        </form>

        <button class="btn btn-primary" onclick="export_history.showModal()">
            <span>RAW EXPORT</span>
            <i class="ri-upload-2-line"></i>
        </button>
    </div>

    @if(request()->hasAny(['my', 'ft', 'kec', 'kel', 'jenis_sekolah', 'slhs_status', 's']))
    <div class="mb-6">
        <!-- Filter Aktif Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-filter-3-line text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800 text-sm">Filter Aktif</h3>
                        <a href="{{ route('history') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors duration-200">
                            <i class="ri-refresh-line text-sm"></i>
                            Reset Semua
                        </a>
                    </div>
                    
                    <!-- Filter Tags -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        @if(request('s'))
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-blue-200 text-blue-800 rounded-lg text-sm shadow-sm">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-search-line text-blue-500"></i>
                                    <span class="font-medium text-gray-600">Pencarian:</span>
                                    <span class="font-semibold">"{{ request('s') }}"</span>
                                </div>
                                <a href="{{ route('history') . '?' . http_build_query(array_diff_key(request()->query(), ['s' => ''])) }}" 
                                   class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-blue-100 hover:bg-blue-200 rounded-full transition-colors duration-200">
                                    <i class="ri-close-line text-xs text-blue-600"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if(request('my'))
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-purple-200 text-purple-800 rounded-lg text-sm shadow-sm">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-calendar-line text-purple-500"></i>
                                    <span class="font-medium text-gray-600">Bulan:</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse(request('my'))->format('F Y') }}</span>
                                </div>
                                <a href="{{ route('history') . '?' . http_build_query(array_diff_key(request()->query(), ['my' => ''])) }}" 
                                   class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-purple-100 hover:bg-purple-200 rounded-full transition-colors duration-200">
                                    <i class="ri-close-line text-xs text-purple-600"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if(request('ft'))
                            @foreach(request('ft') as $form)
                                @php
                                    $formLabels = [
                                        'akomodasi' => 'Akomodasi',
                                        'akomodasi-lain' => 'Akomodasi Lainnya',
                                        'depot-air-minum' => 'Depot Air Minum',
                                        'tempat-olahraga' => 'Gelanggang Olahraga',
                                        'gerai-pangan-jajanan' => 'Gerai Pangan Jajanan',
                                        'gerai-jajanan-keliling' => 'Gerai Pangan Jajanan Keliling',
                                        'jasa-boga-katering' => 'Jasa Boga/Katering',
                                        'renang-pemandian' => 'Kolam Renang',
                                        'puskesmas' => 'Puskesmas',
                                        'restoran' => 'Restoran',
                                        'rumah-makan' => 'Rumah Makan',
                                        'rumah-sakit' => 'Rumah Sakit',
                                        'penyimpanan-air-hujan' => 'SAM Penyimpanan Air Hujan',
                                        'perlindungan-mata-air' => 'SAM Perlindungan Mata Air',
                                        'perpipaan-non-pdam' => 'SAM Perpipaan Non PDAM',
                                        'perpipaan' => 'SAM Perpipaan PDAM',
                                        'sumur-bor-pompa' => 'SAM Sumur Bor dengan Pompa Tangan',
                                        'sumur-gali' => 'SAM Sumur Gali dengan Kerekan',
                                        'sekolah' => 'Sekolah',
                                        'kantin' => 'Sentra Kantin',
                                        'stasiun' => 'Stasiun',
                                        'tempat-rekreasi' => 'Tempat Rekreasi',
                                    ];
                                    $formIcons = [
                                        'akomodasi' => 'ri-home-office-line',
                                        'akomodasi-lain' => 'ri-home-office-fill',
                                        'depot-air-minum' => 'ri-drinks-fill',
                                        'tempat-olahraga' => 'ri-building-4-line',
                                        'gerai-pangan-jajanan' => 'ri-store-line',
                                        'gerai-jajanan-keliling' => 'ri-store-2-line',
                                        'jasa-boga-katering' => 'ri-restaurant-line',
                                        'renang-pemandian' => 'ri-community-line',
                                        'puskesmas' => 'ri-stethoscope-line',
                                        'restoran' => 'ri-restaurant-2-line',
                                        'rumah-makan' => 'ri-home-8-line',
                                        'rumah-sakit' => 'ri-hospital-line',
                                        'penyimpanan-air-hujan' => 'ri-drop-fill',
                                        'perlindungan-mata-air' => 'ri-drop-fill',
                                        'perpipaan-non-pdam' => 'ri-drop-fill',
                                        'perpipaan' => 'ri-drop-fill',
                                        'sumur-bor-pompa' => 'ri-drop-fill',
                                        'sumur-gali' => 'ri-drop-fill',
                                        'sekolah' => 'ri-graduation-cap-line',
                                        'kantin' => 'ri-store-3-line',
                                        'stasiun' => 'ri-train-line',
                                        'tempat-rekreasi' => 'ri-sparkling-line',
                                    ];
                                    $filteredForms = array_filter(request('ft'), fn($f) => $f !== $form);
                                    $queryWithoutThisForm = array_merge(request()->query(), ['ft' => $filteredForms]);
                                    if (empty($filteredForms)) {
                                        unset($queryWithoutThisForm['ft']);
                                    }
                                @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-emerald-200 text-emerald-800 rounded-lg text-sm shadow-sm">
                                    <div class="flex items-center gap-1.5">
                                        <i class="{{ $formIcons[$form] ?? 'ri-file-line' }} text-emerald-500"></i>
                                        <span class="font-semibold">{{ $formLabels[$form] ?? $form }}</span>
                                    </div>
                                    <a href="{{ route('history') . '?' . http_build_query($queryWithoutThisForm) }}" 
                                       class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-emerald-100 hover:bg-emerald-200 rounded-full transition-colors duration-200">
                                        <i class="ri-close-line text-xs text-emerald-600"></i>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        
                        @if(request('slhs_status'))
                            @php
                                $slhsLabels = [
                                    'excellent' => 'Excellent (3+ tahun)',
                                    'good' => 'Good (2+ tahun)',
                                    'caution' => 'Caution (6-12 bulan)',
                                    'warning' => 'Warning (< 6 bulan)',
                                    'critical' => 'Critical (< 1 bulan)',
                                    'expired' => 'Expired',
                                    'no-data' => 'Tidak ada data'
                                ];
                                $slhsColors = [
                                    'excellent' => ['bg' => 'bg-green-100', 'border' => 'border-green-200', 'text' => 'text-green-800', 'icon' => 'text-green-500', 'hover' => 'hover:bg-green-200'],
                                    'good' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'text-blue-500', 'hover' => 'hover:bg-blue-200'],
                                    'caution' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-500', 'hover' => 'hover:bg-yellow-200'],
                                    'warning' => ['bg' => 'bg-orange-100', 'border' => 'border-orange-200', 'text' => 'text-orange-800', 'icon' => 'text-orange-500', 'hover' => 'hover:bg-orange-200'],
                                    'critical' => ['bg' => 'bg-red-100', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => 'text-red-500', 'hover' => 'hover:bg-red-200'],
                                    'expired' => ['bg' => 'bg-gray-100', 'border' => 'border-gray-200', 'text' => 'text-gray-800', 'icon' => 'text-gray-500', 'hover' => 'hover:bg-gray-200'],
                                    'no-data' => ['bg' => 'bg-gray-100', 'border' => 'border-gray-200', 'text' => 'text-gray-800', 'icon' => 'text-gray-500', 'hover' => 'hover:bg-gray-200']
                                ];
                                $colors = $slhsColors[request('slhs_status')] ?? $slhsColors['no-data'];
                            @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white {{ $colors['border'] }} {{ $colors['text'] }} rounded-lg text-sm shadow-sm">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-shield-check-line {{ $colors['icon'] }}"></i>
                                    <span class="font-medium text-gray-600">SLHS:</span>
                                    <span class="font-semibold">{{ $slhsLabels[request('slhs_status')] ?? request('slhs_status') }}</span>
                                </div>
                                <a href="{{ route('history') . '?' . http_build_query(array_diff_key(request()->query(), ['slhs_status' => ''])) }}" 
                                   class="flex-shrink-0 w-5 h-5 flex items-center justify-center {{ $colors['bg'] }} {{ $colors['hover'] }} rounded-full transition-colors duration-200">
                                    <i class="ri-close-line text-xs {{ str_replace('text-', 'text-', $colors['icon']) }}"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if(request('jenis_sekolah'))
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-indigo-200 text-indigo-800 rounded-lg text-sm shadow-sm">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-school-line text-indigo-500"></i>
                                    <span class="font-medium text-gray-600">Jenis Sekolah:</span>
                                    <span class="font-semibold">{{ request('jenis_sekolah') }}</span>
                                </div>
                                <a href="{{ route('history') . '?' . http_build_query(array_diff_key(request()->query(), ['jenis_sekolah' => ''])) }}" 
                                   class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-indigo-100 hover:bg-indigo-200 rounded-full transition-colors duration-200">
                                    <i class="ri-close-line text-xs text-indigo-600"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if(request('kec'))
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-orange-200 text-orange-800 rounded-lg text-sm shadow-sm">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-map-pin-line text-orange-500"></i>
                                    <span class="font-medium text-gray-600">Kecamatan:</span>
                                    <span class="font-semibold">{{ request('kec') }}</span>
                                </div>
                                <a href="{{ route('history') . '?' . http_build_query(array_diff_key(request()->query(), ['kec' => '', 'kel' => ''])) }}" 
                                   class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-orange-100 hover:bg-orange-200 rounded-full transition-colors duration-200">
                                    <i class="ri-close-line text-xs text-orange-600"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if(request('kel'))
                            @foreach(request('kel') as $kelurahan)
                                @php
                                    $filteredKel = array_filter(request('kel'), fn($k) => $k !== $kelurahan);
                                    $queryWithoutThisKel = array_merge(request()->query(), ['kel' => $filteredKel]);
                                    if (empty($filteredKel)) {
                                        unset($queryWithoutThisKel['kel']);
                                    }
                                @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-rose-200 text-rose-800 rounded-lg text-sm shadow-sm">
                                    <div class="flex items-center gap-1.5">
                                        <i class="ri-community-line text-rose-500"></i>
                                        <span class="font-medium text-gray-600">Kelurahan:</span>
                                        <span class="font-semibold">{{ $kelurahan }}</span>
                                    </div>
                                    <a href="{{ route('history') . '?' . http_build_query($queryWithoutThisKel) }}" 
                                       class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-rose-100 hover:bg-rose-200 rounded-full transition-colors duration-200">
                                        <i class="ri-close-line text-xs text-rose-600"></i>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Results Counter -->
                    <div class="flex items-center justify-between pt-2 border-t border-blue-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">
                                Menampilkan 
                                <span class="font-bold text-blue-600">{{ count($inspections) > 0 ? (($page_index - 1) * $dpp + 1) : 0 }} - {{ min($page_index * $dpp, $total_records) }}</span> 
                                dari 
                                <span class="font-bold text-blue-600">{{ number_format($total_records) }}</span> 
                                hasil
                            </span>
                        </div>
                        
                        @if($total_records > 0)
                        <div class="text-xs text-gray-500">
                            Halaman {{ $page_index }} dari {{ $total_pages }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    <th>Status SLHS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if (count($inspections) == 0)
                <tr>
                    <td colspan="8" class="text-center">Tidak ada hasil inspeksi yang dapat ditampilkan</td>
                </tr>
                @endif
                @foreach ($inspections as $index => $inspection)
                <tr>
                    <th>{{ $index + 1 + (($page_index - 1) * $dpp) }}</th>
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
                    <td>
                        <x-status.slhs-badge
                            :slhsExpireDate="$inspection['slhs_expire_date'] ?? null"
                            :slhsIssuedDate="$inspection['slhs_issued_date'] ?? null"
                            :showTooltip="true"
                        />
                    </td>
                    <td class="flex gap-1.5">
                        @auth
                        @if (Auth::user()->role != "USER")
                        <div class="tooltip tooltip-warning" data-tip="Ubah Informasi / Penilaian">
                            <a href="{{ $inspection['sud'] . '/edit' }}" class="btn btn-warning btn-square">
                                <i class="ri-edit-fill"></i>
                            </a>
                        </div>
                        @endif
                        @if (Auth::user()->role == "SUPERADMIN")
                        <form id="deleteForm{{ $index }}" action="{{ url($inspection['sud']) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="tooltip tooltip-error" data-tip="Hapus Hasil Inspeksi">
                                <button type="button" class="btn btn-error btn-outline btn-square" onclick="showDeleteConfirmation('{{ $inspection['name'] }}', {{ $index }})">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </div>
                        </form>
                        @endif
                        @endauth

                        <div class="tooltip" data-tip="Lihat Hasil Inspeksi">
                            <a href="{{ $inspection['sud'] }}" class="btn btn-neutral">
                                <i class="ri-info-i"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="flex gap-3 mt-5 items-center justify-end">
        @auth
        @if (Auth::user()->role == "SUPERADMIN")
        <a href="{{ route('archived') }}" class="btn btn-primary btn-outline">ARCHIVED INSPEKSI</a>
        @endif
        @endauth

        <div class="join">
            @if ($page_index != 1)
            <a href="{{ route('history') . '?' . http_build_query(array_merge(request()->query(), ['p' => (int) $page_index - 1])) }}" class="join-item btn rounded">
                <i class="ri-arrow-left-s-line"></i>
            </a>
            <a href="{{ route('history') . '?' . http_build_query(array_merge(request()->query(), ['p' => (int) $page_index - 1])) }}" class="join-item btn rounded">
                {{ (int) $page_index - 1 }}
            </a>
            @endif
            <button type="button" class="join-item btn rounded btn-active">{{ $page_index }}</button>
            @if ($page_index != $total_pages && $total_pages != 0)
            <a href="{{ route('history') . '?' . http_build_query(array_merge(request()->query(), ['p' => (int) $page_index + 1])) }}" class="join-item btn rounded">
                {{ (int) $page_index + 1 }}
            </a>
            <a href="{{ route('history') . '?' . http_build_query(array_merge(request()->query(), ['p' => (int) $page_index + 1])) }}" class="join-item btn rounded">
                <i class="ri-arrow-right-s-line"></i>
            </a>
            @endif
        </div>
        <form action="{{ route('history') }}" class="join">
            <!-- Preserve all current query parameters except dpp -->
            @foreach (request()->query() as $key => $value)
                @if ($key !== 'dpp')
                    @if (is_array($value))
                        @foreach ($value as $subValue)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endif
            @endforeach
            
            <select class="select select-bordered join-item" name="dpp">
                <option value="5" @if($dpp==5) selected @endif>5</option>
                <option value="15" @if($dpp==15) selected @endif>15</option>
                <option value="25" @if($dpp==25) selected @endif>25</option>
            </select>
            <button type="submit" class="btn btn-neutral join-item rounded">Set</button>
        </form>
    </div>
</div>

<x-modal.filter-history />
<x-modal.export-history />
<x-modal.confirmation />

<script>
    function showDeleteConfirmation(inspectionName, formIndex) {
        showDeleteConfirmationModal(
            'Hapus Hasil Inspeksi',
            `Apakah Anda yakin ingin menghapus hasil inspeksi "${inspectionName}"? Data yang dihapus tidak dapat dikembalikan.`,
            function() {
                document.getElementById('deleteForm' + formIndex).submit();
            }
        );
    }
</script>

@endsection
