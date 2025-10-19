// Get Lat Long Modal JavaScript Functions

let map;
let marker;
let searchTimeout;
let selectedLat = null;
let selectedLng = null;

// Initialize the map
function initMap(lat = -6.2088, lng = 106.8456) {
    // Check if coordinates are provided from server
    if (window.getLatLongData) {
        if (window.getLatLongData.existingLat !== null && window.getLatLongData.existingLng !== null) {
            lat = window.getLatLongData.existingLat;
            lng = window.getLatLongData.existingLng;
        }
    }
    
    // Remove existing map if any
    if (map) {
        map.remove();
    }
    
    map = L.map('map').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // If coordinates are provided, add marker
    if (window.getLatLongData && window.getLatLongData.existingLat !== null && window.getLatLongData.existingLng !== null) {
        marker = L.marker([lat, lng]).addTo(map);
        selectedLat = lat;
        selectedLng = lng;
    }

    // Add click event to map
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng]).addTo(map);
        
        // Store selected coordinates
        selectedLat = lat;
        selectedLng = lng;
        
        // Reverse geocoding to get address
        reverseGeocode(lat, lng);
    });
    
    // Force map to resize properly
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Reverse geocoding function using backend proxy
function reverseGeocode(lat, lng) {
    const url = `/api/geocoding/reverse?lat=${lat}&lon=${lng}`;
    
    console.log('Reverse geocoding for:', lat, lng);
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Reverse geocode response:', data);
            if (data.success && data.data && data.data.display_name) {
                console.log('Address found:', data.data.display_name);
                // Optionally update search input with address
                const addressSearch = document.getElementById('address-search');
                if (addressSearch) {
                    addressSearch.value = data.data.display_name;
                }
            } else {
                console.warn('Reverse geocoding failed:', data.message || 'No address found');
            }
        })
        .catch(error => {
            console.warn('Reverse geocoding error:', error.message);
            // Don't show alert, just log - reverse geocoding is optional
        });
}

// Search for addresses
function searchAddress() {
    const query = document.getElementById('address-search').value.trim();
    const resultsDiv = document.getElementById('search-results');
    
    if (query.length < 3) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    // Show loading
    resultsDiv.innerHTML = '<div class="search-loading"><i class="ri-loader-4-line animate-spin"></i> Mencari...</div>';
    resultsDiv.classList.remove('hidden');
    
    // Debounce search
    searchTimeout = setTimeout(() => {
        const url = `/api/geocoding/search?q=${encodeURIComponent(query)}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Search response:', data); // Debug log
                if (data.success && data.data) {
                    displaySearchResults(data.data);
                } else {
                    console.error('Search failed:', data);
                    resultsDiv.innerHTML = `<div class="search-no-results">${data.message || 'Pencarian gagal. Silakan klik langsung pada peta.'}</div>`;
                }
            })
            .catch(error => {
                console.error('Error searching address:', error);
                resultsDiv.innerHTML = '<div class="search-no-results">Pencarian tidak tersedia. Silakan klik langsung pada peta.</div>';
            });
    }, 500);
}

// Display search results
function displaySearchResults(results) {
    const resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = '';
    
    if (results.length === 0) {
        resultsDiv.innerHTML = '<div class="search-no-results">Tidak ada hasil ditemukan</div>';
        resultsDiv.classList.remove('hidden');
        return;
    }
    
    results.forEach(result => {
        const item = document.createElement('div');
        item.className = 'search-result-item px-4 py-3 cursor-pointer border-b border-gray-200';
        item.innerHTML = `
            <div class="font-medium text-sm">${result.display_name}</div>
        `;
        item.onclick = () => selectSearchResult(result);
        resultsDiv.appendChild(item);
    });
    
    resultsDiv.classList.remove('hidden');
}

// Select search result
function selectSearchResult(result) {
    const lat = parseFloat(result.lat);
    const lng = parseFloat(result.lon);
    
    // Update map view
    map.setView([lat, lng], 16);
    
    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Add new marker
    marker = L.marker([lat, lng]).addTo(map);
    
    // Store selected coordinates
    selectedLat = lat;
    selectedLng = lng;
    
    // Update search input
    document.getElementById('address-search').value = result.display_name;
    
    // Hide search results
    document.getElementById('search-results').classList.add('hidden');
}

// Get current location
function getCurrentLocation() {
    console.log('getCurrentLocation() called');
    
    if (!navigator.geolocation) {
        console.error('Geolocation not supported');
        alert('Geolocation tidak didukung oleh browser ini.');
        return;
    }
    
    // Check if running on HTTPS (required for geolocation in modern browsers)
    if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        console.warn('Geolocation may not work on HTTP (non-localhost)');
        alert('Fitur lokasi memerlukan koneksi HTTPS. Silakan gunakan pencarian atau klik pada peta.');
        return;
    }
    
    const btn = document.getElementById('set-my-lat-long');
    if (!btn) {
        console.error('Button set-my-lat-long not found');
        return;
    }
    
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Mengambil lokasi...';
    btn.disabled = true;
    
    console.log('Requesting geolocation...');
    
    // Flag to track if we got success (to prevent showing error alert after success)
    let gotSuccess = false;
    let errorTimeout = null;
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            console.log('Geolocation success:', position);
            gotSuccess = true;
            
            // Clear any pending error alerts
            if (errorTimeout) {
                clearTimeout(errorTimeout);
                errorTimeout = null;
            }
            
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            console.log('Current location:', lat, lng);
            
            // Update map view
            map.setView([lat, lng], 16);
            
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);
            
            // Store selected coordinates
            selectedLat = lat;
            selectedLng = lng;
            
            // Reverse geocoding
            reverseGeocode(lat, lng);
        },
        function(error) {
            console.error('Geolocation error:', error);
            
            // Don't show error if we already got success
            if (gotSuccess) {
                console.log('Ignoring error because we already got success');
                return;
            }
            
            // Delay showing error alert to give success callback a chance
            // Increased delay from 100ms to 500ms to handle race conditions
            errorTimeout = setTimeout(() => {
                // Double check if success happened during the delay
                if (gotSuccess) {
                    console.log('Not showing error because success happened during delay');
                    return;
                }
                
                // Only reset button if truly failed
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                
                let message = 'Terjadi kesalahan saat mendapatkan lokasi.';
                let details = '';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Akses lokasi ditolak oleh pengguna.';
                        details = 'Silakan izinkan akses lokasi di pengaturan browser Anda, lalu coba lagi.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Informasi lokasi tidak tersedia.';
                        details = 'GPS atau layanan lokasi tidak tersedia. Silakan gunakan pencarian atau klik pada peta.';
                        break;
                    case error.TIMEOUT:
                        message = 'Permintaan lokasi timeout.';
                        details = 'Waktu permintaan habis. Silakan coba lagi atau gunakan pencarian.';
                        break;
                }
                
                console.error(`Geolocation error: ${message}`, details);
                alert(`${message}\n\n${details}`);
            }, 500); // Wait 500ms before showing error (increased from 100ms)
        },
        {
            enableHighAccuracy: false, // Changed to false for faster response
            timeout: 15000, // Increased timeout
            maximumAge: 30000 // Allow cached location up to 30 seconds
        }
    );
}

// Apply selected coordinates
function applyCoordinates() {
    if (!selectedLat || !selectedLng) {
        alert('Silakan pilih lokasi terlebih dahulu dengan klik pada peta.');
        return;
    }
    
    // Set coordinates to the koordinat input field
    const koordinatInput = document.querySelector('input[name="koordinat"]');
    if (koordinatInput) {
        koordinatInput.value = `${selectedLat}, ${selectedLng}`;
    }
    
    // Close modal using DaisyUI method
    const modal = document.getElementById('get_lat_long');
    if (modal) {
        modal.close();
    }
}

// Initialize GPS modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map when modal is opened
    const modal = document.getElementById('get_lat_long');
    if (modal) {
        modal.addEventListener('click', function(e) {
            // Initialize map when modal is shown
            if (e.target === modal && !map) {
                setTimeout(() => {
                    initMap();
                }, 100);
            }
        });
    }
    
    // Add event listener for address search
    const addressSearch = document.getElementById('address-search');
    if (addressSearch) {
        addressSearch.addEventListener('input', searchAddress);
        
        // Add search button listener
        const searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', searchAddress);
        }
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            const searchResults = document.getElementById('search-results');
            if (searchResults && !addressSearch.contains(e.target) && !searchResults.contains(e.target) && !document.getElementById('search-btn').contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
    
    // Add event listener for "Use My Location" button
    const myLocationBtn = document.getElementById('set-my-lat-long');
    if (myLocationBtn) {
        myLocationBtn.addEventListener('click', getCurrentLocation);
    }
    
    // Add event listener for "Apply" button
    const applyBtn = document.getElementById('set-lat-long');
    if (applyBtn) {
        applyBtn.addEventListener('click', applyCoordinates);
    }
    
    // Observe when modal is opened to initialize map
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'open') {
                const modal = mutation.target;
                if (modal.hasAttribute('open') && !map) {
                    setTimeout(() => {
                        initMap();
                    }, 100);
                }
            }
        });
    });
    
    if (modal) {
        observer.observe(modal, {
            attributes: true
        });
    }
});