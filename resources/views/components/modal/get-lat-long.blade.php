<dialog id="get_lat_long" class="modal">
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
        <button>close</button>
    </form>
</dialog>

<style>
    /* Custom styles for search results */
    #search-results {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        /* Ensure dropdown is positioned correctly below input */
        top: calc(100% + 0.25rem) !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
    }
    
    .search-result-item {
        transition: background-color 0.15s ease-in-out;
    }
    
    .search-result-item:hover {
        background-color: #f3f4f6;
    }
    
    .search-result-item.active {
        background-color: #dbeafe !important;
    }
    
    .search-result-item:last-child {
        border-bottom: none;
    }
    
    /* Loading indicator */
    .search-loading {
        padding: 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    /* No results indicator */
    .search-no-results {
        padding: 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    /* Ensure modal and elements have proper z-index stacking */
    .modal-box {
        position: relative;
        z-index: 1000;
    }
    
    /* Make sure search input area has enough space for dropdown */
    .search-input-container {
        position: relative;
        z-index: 1001;
        margin-bottom: 1rem;
    }
    
    /* Dropdown positioning and z-index */
    #search-results {
        z-index: 1002 !important;
        max-height: 240px;
        overflow-y: auto;
    }
    
    /* Animation for dropdown */
    #search-results {
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
    }
    
    #search-results:not(.hidden) {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Spinning animation for loading */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Prevent dropdown from overlapping with map */
    #map {
        margin-top: 1.5rem !important;
        clear: both;
    }
    
    /* Make search input area more spacious */
    .input-group {
        margin-bottom: 1rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        #search-results {
            max-height: 200px;
        }
    }
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function() {
        let map;
        let prevMark;
        // Default coordinates (Depok, Indonesia)
        let lat = -6.3942735061950575;
        let lng = 106.82102259514994;
        let mapInitialized = false;
        let searchTimeout;

        // Nominatim geocoding function
        async function searchAddress(query) {
            if (query.length < 3) return [];
            
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5&addressdetails=1`);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error searching address:', error);
                return [];
            }
        }

        // Display search results
        function displaySearchResults(results) {
            const resultsContainer = $('#search-results');
            resultsContainer.empty();

            if (results.length === 0) {
                resultsContainer.html('<div class="search-no-results">Alamat tidak ditemukan. Coba kata kunci lain.</div>');
                resultsContainer.removeClass('hidden');
                return;
            }

            results.forEach(result => {
                const resultItem = $(`
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 search-result-item"
                         data-lat="${result.lat}" data-lon="${result.lon}">
                        <div class="font-medium text-sm">${result.display_name}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            ${result.type ? result.type.charAt(0).toUpperCase() + result.type.slice(1) : 'Lokasi'}
                        </div>
                    </div>
                `);
                resultsContainer.append(resultItem);
            });

            resultsContainer.removeClass('hidden');
            
            // Ensure dropdown is positioned correctly when shown
            setTimeout(adjustDropdownPosition, 5);
        }

        // Show loading indicator
        function showSearchLoading() {
            const resultsContainer = $('#search-results');
            resultsContainer.html('<div class="search-loading"><i class="ri-loader-4-line animate-spin"></i> Mencari alamat...</div>');
            resultsContainer.removeClass('hidden');
            
            // Ensure dropdown is positioned correctly when shown
            setTimeout(adjustDropdownPosition, 5);
        }

        // Handle search input
        $('#address-search').on('input', function() {
            const query = $(this).val();
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            if (query.length < 3) {
                $('#search-results').addClass('hidden');
                return;
            }

            // Show loading immediately
            showSearchLoading();

            // Debounce search
            searchTimeout = setTimeout(async () => {
                const results = await searchAddress(query);
                displaySearchResults(results);
            }, 300);
        });

        // Handle search result selection and hover
        $(document).on('click', '.search-result-item', function() {
            const selectedLat = parseFloat($(this).data('lat'));
            const selectedLon = parseFloat($(this).data('lon'));
            const displayName = $(this).find('.font-medium').text();

            // Update coordinates
            lat = selectedLat;
            lng = selectedLon;

            // Update input field
            $('#address-search').val(displayName);

            // Hide results
            $('#search-results').addClass('hidden');

            // Update map
            if (map) {
                map.setView([lat, lng], 16);
                if (prevMark) {
                    prevMark.remove();
                }
                prevMark = L.marker([lat, lng]).addTo(map);
            }
        });

        // Handle mouse hover on search results
        $(document).on('mouseenter', '.search-result-item', function() {
            $('.search-result-item').removeClass('active bg-blue-100');
            $(this).addClass('active bg-blue-100');
        });

        $(document).on('mouseleave', '.search-result-item', function() {
            $(this).removeClass('active bg-blue-100');
        });

        // Handle search button click
        $('#search-btn').on('click', async function() {
            const query = $('#address-search').val();
            if (query.length < 3) {
                alert('Masukkan minimal 3 karakter untuk pencarian');
                return;
            }

            showSearchLoading();
            const results = await searchAddress(query);
            displaySearchResults(results);
        });

        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#address-search, #search-results, #search-btn').length) {
                $('#search-results').addClass('hidden');
            }
        });

        // Ensure dropdown positioning is correct
        function adjustDropdownPosition() {
            const $input = $('#address-search');
            const $dropdown = $('#search-results');
            
            if ($input.length && $dropdown.length) {
                const inputOffset = $input.offset();
                const inputHeight = $input.outerHeight();
                
                // Make sure dropdown is positioned correctly relative to input
                $dropdown.css({
                    'position': 'absolute',
                    'top': '100%',
                    'left': '0',
                    'right': '0',
                    'margin-top': '0.25rem',
                    'z-index': '1002'
                });
            }
        }

        // Handle Enter key in search input and arrow navigation
        $('#address-search').on('keydown', function(e) {
            const $results = $('#search-results .search-result-item');
            const $active = $results.filter('.active');
            
            switch(e.key) {
                case 'Enter':
                    e.preventDefault();
                    if ($active.length > 0) {
                        $active.click();
                    } else {
                        $('#search-btn').click();
                    }
                    break;
                    
                case 'ArrowDown':
                    e.preventDefault();
                    if ($active.length === 0) {
                        $results.first().addClass('active bg-blue-100');
                    } else {
                        $active.removeClass('active bg-blue-100');
                        const $next = $active.next('.search-result-item');
                        if ($next.length > 0) {
                            $next.addClass('active bg-blue-100');
                        } else {
                            $results.first().addClass('active bg-blue-100');
                        }
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if ($active.length === 0) {
                        $results.last().addClass('active bg-blue-100');
                    } else {
                        $active.removeClass('active bg-blue-100');
                        const $prev = $active.prev('.search-result-item');
                        if ($prev.length > 0) {
                            $prev.addClass('active bg-blue-100');
                        } else {
                            $results.last().addClass('active bg-blue-100');
                        }
                    }
                    break;
                    
                case 'Escape':
                    $('#search-results').addClass('hidden');
                    break;
            }
        });

        // Initialize map when modal is opened
        function initializeMap() {
            // Check if there are existing coordinates in the input field
            const existingCoords = $('#koordinat').val();
            if (existingCoords && existingCoords.trim() !== '') {
                const coords = existingCoords.split(',');
                if (coords.length === 2) {
                    const existingLat = parseFloat(coords[0].trim());
                    const existingLng = parseFloat(coords[1].trim());
                    if (!isNaN(existingLat) && !isNaN(existingLng)) {
                        lat = existingLat;
                        lng = existingLng;
                    }
                }
            }

            if (!mapInitialized) {
                map = L.map('map', {
                    center: [lat, lng],
                    zoom: 16
                });
                
                // Load and display tile layers on the map (OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add initial marker
                prevMark = L.marker([lat, lng]).addTo(map);

                // Add click event to the map
                map.on('click', function(e) {
                    // Get the latitude and longitude
                    lat = e.latlng.lat;
                    lng = e.latlng.lng;

                    // Remove previous marker and add new one
                    if (prevMark) {
                        prevMark.remove();
                    }
                    prevMark = L.marker([lat, lng]).addTo(map);

                    // Reverse geocoding to get address
                    reverseGeocode(lat, lng);
                });

                mapInitialized = true;
            } else {
                // Reset map view and marker to current coordinates
                map.setView([lat, lng], 16);
                if (prevMark) {
                    prevMark.remove();
                }
                prevMark = L.marker([lat, lng]).addTo(map);
            }
        }

        // Reverse geocoding function
        async function reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                const data = await response.json();
                if (data && data.display_name) {
                    $('#address-search').val(data.display_name);
                }
            } catch (error) {
                console.error('Error reverse geocoding:', error);
            }
        }

        // Handle modal open event using MutationObserver and dialog events
        const modal = document.getElementById('get_lat_long');
        
        // Listen for when modal is opened
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.close();
            }
        });

        // Use a global function to initialize map when modal opens
        window.openMapModal = function() {
            // Save form data sebelum membuka modal untuk mencegah kehilangan data
            if (window.autoSaveForm) {
                window.autoSaveForm.manualSave();
            }
            
            modal.showModal();
            setTimeout(function() {
                initializeMap();
                if (map) {
                    map.invalidateSize();
                }
            }, 100);
        };

        // Override the existing showModal to include map initialization
        const originalShowModal = modal.showModal;
        modal.showModal = function() {
            // Save form data sebelum membuka modal untuk mencegah kehilangan data
            if (window.autoSaveForm) {
                window.autoSaveForm.manualSave();
            }
            
            originalShowModal.call(this);
            setTimeout(function() {
                initializeMap();
                if (map) {
                    map.invalidateSize();
                }
                // Clear search field when modal opens
                $('#address-search').val('');
                $('#search-results').addClass('hidden');
                
                // Ensure dropdown positioning is correct
                adjustDropdownPosition();
            }, 100);
        };

        // Get current location
        $('#set-my-lat-long').click(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        lat = position.coords.latitude;
                        lng = position.coords.longitude;
                        
                        // Update map view and marker
                        if (map) {
                            map.setView([lat, lng], 16);
                            if (prevMark) {
                                prevMark.remove();
                            }
                            prevMark = L.marker([lat, lng]).addTo(map);
                        }
                        
                        // Reverse geocode to get address
                        reverseGeocode(lat, lng);
                        
                        // Set coordinates to input
                        $('#koordinat').val(`${lat}, ${lng}`);
                        
                        // Close modal
                        get_lat_long.close();
                    },
                    function(error) {
                        let errorMessage = '';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Akses lokasi ditolak oleh pengguna.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Informasi lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "Waktu tunggu habis untuk mendapatkan lokasi.";
                                break;
                            default:
                                errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                                break;
                        }
                        alert("Error: " + errorMessage);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        });

        // Apply selected coordinates
        $('#set-lat-long').click(function() {
            // Validate coordinates before applying
            if (lat !== undefined && lng !== undefined && !isNaN(lat) && !isNaN(lng)) {
                $('#koordinat').val(`${lat}, ${lng}`);
                get_lat_long.close();
            } else {
                alert("Silakan pilih lokasi di peta terlebih dahulu.");
            }
        });

        // Adjust dropdown position on window resize
        $(window).on('resize', function() {
            adjustDropdownPosition();
        });

        // Also adjust when input gets focus
        $('#address-search').on('focus', function() {
            setTimeout(adjustDropdownPosition, 10);
        });
    })
</script>