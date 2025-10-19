<link rel="stylesheet" href="{{ asset('css/modal/get-lat-long.css') }}" />

<dialog id="get_lat_long" class="modal" data-existing-lat="{{ $lat ?? 'null' }}" data-existing-lng="{{ $lng ?? 'null' }}">
    <div class="modal-box max-w-[50rem]">
        <h3 class="font-bold text-lg">Cari Titik GPS</h3>

        <!-- Search Box -->
        <div class="mt-4 mb-6">
            <div class="input-group">
                <label for="address-search" class="text-sm font-medium mb-2 block">Cari Alamat:</label>
                <div class="relative search-input-container">
                    <input
                        type="text"
                        id="address-search"
                        class="input input-bordered w-full pr-10"
                        placeholder="Masukkan nama jalan, gedung, atau alamat..."
                        autocomplete="off"
                    />
                    <button type="button" id="search-btn" class="absolute right-2 top-1/2 transform -translate-y-1/2 btn btn-ghost btn-sm">
                        <i class="ri-search-line"></i>
                    </button>
                    <!-- Search Results Dropdown - Positioned below input -->
                    <div id="search-results" class="absolute top-full left-0 right-0 z-[1002] bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                    </div>
                </div>
            </div>
        </div>

        <div id="map" class="w-full h-96 mt-4 clear-both">
        </div>

        <div class="flex justify-end gap-1.5 mt-6">
            <button type="button" id="set-my-lat-long" class="btn btn-primary btn-outline">
                <span>GUNAKAN LOKASI SAYA</span>
                <i class="ri-map-pin-2-line"></i>
            </button>
            <button type="button" id="set-lat-long" class="btn btn-primary">TERAPKAN</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        @csrf
        <button>close</button>
    </form>
</dialog>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/modal/get-lat-long-init.js') }}"></script>
<script src="{{ asset('js/modal/get-lat-long.js') }}"></script>