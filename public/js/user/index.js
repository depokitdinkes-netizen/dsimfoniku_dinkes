// User Index Page JavaScript Functions

// Debounce function for search input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Function to open delete modal with user information
function openDeleteModal(userId, userName, userEmail, userRole) {
    // Set the form action dynamically
    const form = document.getElementById('deleteForm');
    const baseUrl = window.userIndexData.deleteBaseUrl;
    form.action = baseUrl + '/' + userId;
    
    // Set user information in modal
    document.getElementById('userName').textContent = userName;
    document.getElementById('userEmail').textContent = userEmail;
    document.getElementById('userRole').textContent = userRole;
    
    // Show the modal
    $('#deleteModal').modal('show');
}

// Initialize page functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get data passed from Blade template
    const { deleteBaseUrl, csrfToken } = window.userIndexData || {};
    
    // Filter kelurahan functionality
    const filterKelurahan = document.getElementById('filter-kelurahan');
    if (filterKelurahan) {
        filterKelurahan.addEventListener('change', function() {
            const selectedKelurahan = this.value;
            const currentUrl = new URL(window.location.href);
            
            if (selectedKelurahan) {
                currentUrl.searchParams.set('kelurahan', selectedKelurahan);
            } else {
                currentUrl.searchParams.delete('kelurahan');
            }
            
            // Reset to first page when filtering
            currentUrl.searchParams.delete('page');
            
            window.location.href = currentUrl.toString();
        });
    }
    
    // Search user functionality with debounce
    const searchUser = document.getElementById('search-user');
    if (searchUser) {
        const debouncedSearch = debounce(function() {
            const searchValue = searchUser.value;
            const currentUrl = new URL(window.location.href);
            
            if (searchValue.trim()) {
                currentUrl.searchParams.set('search', searchValue);
            } else {
                currentUrl.searchParams.delete('search');
            }
            
            // Reset to first page when searching
            currentUrl.searchParams.delete('page');
            
            window.location.href = currentUrl.toString();
        }, 500);
        
        searchUser.addEventListener('input', debouncedSearch);
    }
});

// Make openDeleteModal globally available
window.openDeleteModal = openDeleteModal;