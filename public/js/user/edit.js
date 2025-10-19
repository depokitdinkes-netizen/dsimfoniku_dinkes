// User Edit Page JavaScript Functions

// Function to toggle kelurahan field visibility based on user role
function toggleKelurahanFieldEdit() {
    const roleSelect = document.getElementById('role');
    const kelurahanContainer = document.getElementById('kelurahan-container');
    const kelurahanContainerAdmin = document.getElementById('kelurahan-container-admin');
    const baris5Container = document.getElementById('baris5');
    
    if (!roleSelect) return;
    
    const selectedRole = roleSelect.value;
    
    if (selectedRole === 'ADMIN') {
        if (kelurahanContainer) kelurahanContainer.style.display = 'block';
        if (kelurahanContainerAdmin) kelurahanContainerAdmin.style.display = 'none';
        if (baris5Container) baris5Container.style.display = 'block';
    } else if (selectedRole === 'SUPERADMIN') {
        if (kelurahanContainer) kelurahanContainer.style.display = 'none';
        if (kelurahanContainerAdmin) kelurahanContainerAdmin.style.display = 'none';
        if (baris5Container) baris5Container.style.display = 'none';
    } else {
        if (kelurahanContainer) kelurahanContainer.style.display = 'none';
        if (kelurahanContainerAdmin) kelurahanContainerAdmin.style.display = 'none';
        if (baris5Container) baris5Container.style.display = 'none';
    }
}

// Function to add kelurahan field (for superadmin editing admin)
function addKelurahanFieldEdit() {
    const container = document.getElementById('kelurahan-container');
    if (!container) return;
    
    const kecSelect = document.getElementById('kec');
    const selectedKecamatan = kecSelect ? kecSelect.value : '';
    
    // Ambil options dari dropdown pertama sebagai template
    const firstSelect = container.querySelector('.kelurahan-select');
    let optionsHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
    
    if (firstSelect && firstSelect.options.length > 1) {
        // Copy semua options dari dropdown pertama
        Array.from(firstSelect.options).forEach(option => {
            if (option.value !== '') {
                optionsHTML += `<option value="${option.value}">${option.textContent}</option>`;
            }
        });
    }
    
    const fieldCount = container.children.length;
    const newField = document.createElement('div');
    newField.className = 'kelurahan-row flex gap-2 mb-2';
    newField.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
            ${optionsHTML}
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEdit(this)">-</button>
    `;
    
    container.appendChild(newField);
    
    // Add event listener untuk select yang baru dibuat
    const newSelect = newField.querySelector('.kelurahan-select');
    if (newSelect) {
        newSelect.addEventListener('change', function() {
            updateKelurahanOptionsEdit();
            checkKelurahanDuplicatesEdit();
        });
    }
    
    // Update semua dropdown untuk hide kelurahan yang sudah dipilih
    updateKelurahanOptionsEdit();
    
    // Check duplikasi
    checkKelurahanDuplicatesEdit();
}

// Function to remove kelurahan field (for superadmin editing admin)
function removeKelurahanFieldEdit(button) {
    const container = document.getElementById('kelurahan-container');
    if (!container || container.children.length <= 1) return;
    
    // Simpan nilai kelurahan yang sudah dipilih sebelum menghapus
    const existingValues = [];
    container.querySelectorAll('.kelurahan-select').forEach(select => {
        existingValues.push(select.value);
    });
    
    // Hapus field yang diklik
    const rowToRemove = button.closest('.kelurahan-row');
    const indexToRemove = Array.from(container.children).indexOf(rowToRemove);
    rowToRemove.remove();
    
    // Hapus nilai yang dihapus dari array
    existingValues.splice(indexToRemove, 1);
    
    // Restore nilai yang tersisa
    setTimeout(() => {
        container.querySelectorAll('.kelurahan-select').forEach((select, index) => {
            if (existingValues[index]) {
                select.value = existingValues[index];
            }
        });
    }, 100);
    
    checkKelurahanDuplicatesEdit();
}

// Function to add kelurahan field (for admin editing themselves)
function addKelurahanFieldEditAdmin() {
    const container = document.getElementById('kelurahan-container-admin');
    if (!container) return;
    
    const kecSelect = document.getElementById('kec-admin');
    const selectedKecamatan = kecSelect ? kecSelect.value : '';
    
    // Ambil options dari dropdown pertama sebagai template
    const firstSelect = container.querySelector('.kelurahan-select-admin');
    let optionsHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
    
    if (firstSelect && firstSelect.options.length > 1) {
        // Copy semua options dari dropdown pertama
        Array.from(firstSelect.options).forEach(option => {
            if (option.value !== '') {
                optionsHTML += `<option value="${option.value}">${option.textContent}</option>`;
            }
        });
    }
    
    const fieldCount = container.children.length;
    const newField = document.createElement('div');
    newField.className = 'kelurahan-row flex gap-2 mb-2';
    newField.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select-admin">
            ${optionsHTML}
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEditAdmin(this)">-</button>
    `;
    
    container.appendChild(newField);
    
    // Add event listener untuk select yang baru dibuat
    const newSelect = newField.querySelector('.kelurahan-select-admin');
    if (newSelect) {
        newSelect.addEventListener('change', function() {
            updateKelurahanOptionsEditAdmin();
            checkKelurahanDuplicatesEditAdmin();
        });
    }
    
    // Update semua dropdown untuk hide kelurahan yang sudah dipilih
    updateKelurahanOptionsEditAdmin();
    
    // Check duplikasi
    checkKelurahanDuplicatesEditAdmin();
}

// Function to remove kelurahan field (for admin editing themselves)
function removeKelurahanFieldEditAdmin(button) {
    const container = document.getElementById('kelurahan-container-admin');
    if (!container || container.children.length <= 1) return;
    
    // Simpan nilai kelurahan yang sudah dipilih sebelum menghapus
    const existingValues = [];
    container.querySelectorAll('.kelurahan-select-admin').forEach(select => {
        existingValues.push(select.value);
    });
    
    // Hapus field yang diklik
    const rowToRemove = button.closest('.kelurahan-row');
    const indexToRemove = Array.from(container.children).indexOf(rowToRemove);
    rowToRemove.remove();
    
    // Hapus nilai yang dihapus dari array
    existingValues.splice(indexToRemove, 1);
    
    // Restore nilai yang tersisa
    setTimeout(() => {
        container.querySelectorAll('.kelurahan-select-admin').forEach((select, index) => {
            if (existingValues[index]) {
                select.value = existingValues[index];
            }
        });
    }, 100);
    
    // Update dropdown options setelah remove
    updateKelurahanOptionsEditAdmin();
}

// Function to reset kelurahan container
function resetKelurahanContainerEdit() {
    const container = document.getElementById('kelurahan-container');
    if (!container) return;
    
    // Remove all but first field
    while (container.children.length > 1) {
        container.removeChild(container.lastChild);
    }
    
    // Reset first field
    const firstSelect = container.querySelector('.kelurahan-select');
    if (firstSelect) {
        firstSelect.selectedIndex = 0;
    }
}

// Function to update kelurahan options (for superadmin editing admin)
function updateKelurahanOptionsEdit() {
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select');
    if (kelurahanSelects.length === 0) return;
    
    // Get all selected kelurahan values
    const selectedKelurahan = [];
    kelurahanSelects.forEach(select => {
        if (select.value && select.value !== '') {
            selectedKelurahan.push(select.value);
        }
    });
    
    // console.log('Selected kelurahan in edit:', selectedKelurahan);
    
    // Update all kelurahan selects
    kelurahanSelects.forEach(select => {
        const currentValue = select.value;
        
        // Update visibility/availability dari options
        Array.from(select.options).forEach(option => {
            if (option.value === '') return; // Skip placeholder
            
            // Hide/show option berdasarkan apakah sudah dipilih di select lain
            if (selectedKelurahan.includes(option.value) && currentValue !== option.value) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = 'block';
                option.disabled = false;
            }
        });
    });
    
    // Check for duplicates
    checkKelurahanDuplicatesEdit();
}

// Function to check for duplicate kelurahan selections
function checkKelurahanDuplicatesEdit() {
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select');
    const selectedValues = [];
    
    kelurahanSelects.forEach(select => {
        const value = select.value;
        if (value) {
            if (selectedValues.includes(value)) {
                select.style.borderColor = '#dc3545';
                select.style.backgroundColor = '#f8d7da';
            } else {
                select.style.borderColor = '';
                select.style.backgroundColor = '';
                selectedValues.push(value);
            }
        } else {
            select.style.borderColor = '';
            select.style.backgroundColor = '';
        }
    });
}

// Function to update kelurahan options (for admin editing themselves)
function updateKelurahanOptionsEditAdmin() {
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select-admin');
    if (kelurahanSelects.length === 0) return;
    
    // Get all selected kelurahan values
    const selectedKelurahan = [];
    kelurahanSelects.forEach(select => {
        if (select.value && select.value !== '') {
            selectedKelurahan.push(select.value);
        }
    });
    
    // console.log('Selected kelurahan in edit admin:', selectedKelurahan);
    
    // Update all kelurahan selects
    kelurahanSelects.forEach(select => {
        const currentValue = select.value;
        
        // Update visibility/availability dari options
        Array.from(select.options).forEach(option => {
            if (option.value === '') return; // Skip placeholder
            
            // Hide/show option berdasarkan apakah sudah dipilih di select lain
            if (selectedKelurahan.includes(option.value) && currentValue !== option.value) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = 'block';
                option.disabled = false;
            }
        });
    });
    
    // Check for duplicates
    checkKelurahanDuplicatesEditAdmin();
}

// Function to check for duplicate kelurahan selections (admin)
function checkKelurahanDuplicatesEditAdmin() {
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select-admin');
    const selectedValues = [];
    
    kelurahanSelects.forEach(select => {
        const value = select.value;
        if (value) {
            if (selectedValues.includes(value)) {
                select.style.borderColor = '#dc3545';
                select.style.backgroundColor = '#f8d7da';
            } else {
                select.style.borderColor = '';
                select.style.backgroundColor = '';
                selectedValues.push(value);
            }
        } else {
            select.style.borderColor = '';
            select.style.backgroundColor = '';
        }
    });
}

// Function to find and set multiple kelurahan
function findAndSetMultipleKelurahan(userKelurahan) {
    // console.log('findAndSetMultipleKelurahan called with:', userKelurahan);
    
    if (!userKelurahan || userKelurahan.length === 0) {
        // console.log('No userKelurahan data');
        return;
    }
    
    const kecamatanValue = userKelurahan[0].kecamatan;
    // console.log('Setting kecamatan to:', kecamatanValue);
    
    // Set hidden input untuk kecamatan
    const hiddenInput = document.getElementById('selected-kecamatan');
    if (hiddenInput) {
        hiddenInput.value = kecamatanValue;
    }
    
    // Cari dan set kecamatan
    const kecSelect = document.getElementById('kec');
    if (kecSelect) {
        let found = false;
        Array.from(kecSelect.options).forEach(option => {
            if (option.value === kecamatanValue || option.text === kecamatanValue) {
                option.selected = true;
                found = true;
            }
        });
        
        if (found) {
            // console.log('Kecamatan found, triggering change event');
            // Trigger change dengan jQuery untuk memastikan event handler terpanggil
            $(kecSelect).trigger('change');
            
            // Setelah kelurahan dimuat, set semua kelurahan yang dipilih
            setTimeout(() => {
                const kelurahanSelects = document.querySelectorAll('.kelurahan-select');
                // console.log('Found kelurahan selects:', kelurahanSelects.length);
                
                kelurahanSelects.forEach((select, index) => {
                    if (userKelurahan[index]) {
                        const kelurahanValue = userKelurahan[index].kelurahan;
                        // console.log(`Setting kelurahan ${index} to:`, kelurahanValue);
                        
                        let kelurahanFound = false;
                        Array.from(select.options).forEach(option => {
                            if (option.value === kelurahanValue || option.text === kelurahanValue) {
                                option.selected = true;
                                kelurahanFound = true;
                            }
                        });
                        
                        if (kelurahanFound) {
                            // console.log(`Kelurahan ${index} set successfully`);
                        } else {
                            // console.log(`Kelurahan ${index} not found in options`);
                        }
                    }
                });
            }, 2500);
        } else {
            // console.log('Kecamatan not found in options');
        }
    } else {
        // console.log('Kecamatan select element not found');
    }
}

// Function to find and set multiple kelurahan (Admin edit sendiri)
function findAndSetMultipleKelurahanAdmin(userKelurahan) {
    // console.log('findAndSetMultipleKelurahanAdmin called with:', userKelurahan);
    
    if (!userKelurahan || userKelurahan.length === 0) {
        // console.log('No userKelurahan data for admin');
        return;
    }
    
    const kecamatanValue = userKelurahan[0].kecamatan;
    // console.log('Setting kecamatan admin to:', kecamatanValue);
    
    // Set hidden input untuk kecamatan
    const hiddenInput = document.getElementById('selected-kecamatan-admin');
    if (hiddenInput) {
        hiddenInput.value = kecamatanValue;
    }
    
    // Cari dan set kecamatan
    const kecSelect = document.getElementById('kec-admin');
    if (kecSelect) {
        let found = false;
        Array.from(kecSelect.options).forEach(option => {
            if (option.value === kecamatanValue || option.text === kecamatanValue) {
                option.selected = true;
                found = true;
            }
        });
        
        if (found) {
            // console.log('Kecamatan admin found, triggering change event');
            // Trigger change dengan jQuery untuk memastikan event handler terpanggil
            $(kecSelect).trigger('change');
            
            // Setelah kelurahan dimuat, set semua kelurahan yang dipilih
            setTimeout(() => {
                const kelurahanSelects = document.querySelectorAll('.kelurahan-select-admin');
                // console.log('Found kelurahan admin selects:', kelurahanSelects.length);
                
                kelurahanSelects.forEach((select, index) => {
                    if (userKelurahan[index]) {
                        const kelurahanValue = userKelurahan[index].kelurahan;
                        // console.log(`Setting kelurahan admin ${index} to:`, kelurahanValue);
                        
                        let kelurahanFound = false;
                        Array.from(select.options).forEach(option => {
                            if (option.value === kelurahanValue || option.text === kelurahanValue) {
                                option.selected = true;
                                kelurahanFound = true;
                            }
                        });
                        
                        if (kelurahanFound) {
                            // console.log(`Kelurahan admin ${index} set successfully`);
                        } else {
                            // console.log(`Kelurahan admin ${index} not found in options`);
                        }
                    }
                });
            }, 2500);
        } else {
            // console.log('Kecamatan admin not found in options');
        }
    } else {
        // console.log('Kecamatan admin select element not found');
    }
}

// Function to find and set kecamatan by kelurahan
function findAndSetKecamatanByKelurahan(kelurahanValue, kecamatanValue) {
    // Jika kecamatan sudah ada, set langsung
    if (kecamatanValue) {
        // Set hidden input untuk kecamatan
        document.getElementById('selected-kecamatan').value = kecamatanValue;
        
        // Cari dan set kecamatan
        const kecSelect = document.getElementById('kec');
        if (kecSelect) {
            Array.from(kecSelect.options).forEach(option => {
                if (option.value === kecamatanValue || option.text === kecamatanValue) {
                    option.selected = true;
                    kecSelect.dispatchEvent(new Event('change'));
                    
                    // Setelah kelurahan dimuat, set kelurahan yang dipilih
                    setTimeout(() => {
                        const firstKelurahanSelect = document.querySelector('.kelurahan-select');
                        if (firstKelurahanSelect) {
                            Array.from(firstKelurahanSelect.options).forEach(option => {
                                if (option.value === kelurahanValue || option.text === kelurahanValue) {
                                    option.selected = true;
                                    return;
                                }
                            });
                        }
                    }, 1000);
                    
                    return;
                }
            });
        }
    }
}

// Function to find and set kecamatan by kelurahan (Admin edit sendiri)
function findAndSetKecamatanByKelurahanAdmin(kelurahanValue, kecamatanValue) {
    // Jika kecamatan sudah ada, set langsung
    if (kecamatanValue) {
        // Set hidden input untuk kecamatan
        document.getElementById('selected-kecamatan-admin').value = kecamatanValue;
        
        // Cari dan set kecamatan
        const kecSelect = document.getElementById('kec-admin');
        if (kecSelect) {
            Array.from(kecSelect.options).forEach(option => {
                if (option.value === kecamatanValue || option.text === kecamatanValue) {
                    option.selected = true;
                    kecSelect.dispatchEvent(new Event('change'));
                    
                    // Setelah kelurahan dimuat, set kelurahan yang dipilih
                    setTimeout(() => {
                        const firstKelurahanSelect = document.querySelector('.kelurahan-select-admin');
                        if (firstKelurahanSelect) {
                            Array.from(firstKelurahanSelect.options).forEach(option => {
                                if (option.value === kelurahanValue || option.text === kelurahanValue) {
                                    option.selected = true;
                                    return;
                                }
                            });
                        }
                    }, 1000);
                    
                    return;
                }
            });
        }
    }
}

// Function to preview kop surat
function previewKopSurat() {
    // Ambil data dari semua baris kop surat (1-10)
    const formData = {};
    
    for (let i = 1; i <= 10; i++) {
        const sizeInput = document.getElementById(`sizebaris${i}`);
        const textInput = document.getElementById(`baris${i}`);
        
        if (sizeInput && textInput) {
            formData[`sizebaris${i}`] = sizeInput.value || '13px';
            formData[`baris${i}`] = textInput.value || '';
        }
    }
    
    // Buat URL dengan parameter
    const params = new URLSearchParams(formData);
    const { kopSuratPreviewRoute } = window.userEditData || {};
    const url = `${kopSuratPreviewRoute}?${params.toString()}`;
    
    // Buka PDF di tab baru
    window.open(url, '_blank');
}

// Function to add kop line
function addKopLine() {
    const container = document.getElementById('kop-surat-container');
    if (!container) return;
    
    const hiddenLines = container.querySelectorAll('.kop-line-group.hidden');
    
    if (hiddenLines.length > 0) {
        // Ambil baris tersembunyi pertama dan tampilkan
        const nextLine = hiddenLines[0];
        nextLine.classList.remove('hidden');
        
        // Update button state
        updateAddButtonState();
    }
}

// Function to remove kop line
function removeKopLine(lineNumber) {
    if (lineNumber <= 4) return; // Tidak bisa hapus baris 1-4
    
    const lineGroup = document.querySelector(`[data-line="${lineNumber}"]`);
    if (lineGroup) {
        // Clear inputs
        const sizeInput = lineGroup.querySelector(`#sizebaris${lineNumber}`);
        const textInput = lineGroup.querySelector(`#baris${lineNumber}`);
        if (sizeInput) sizeInput.value = '13px';
        if (textInput) textInput.value = '';
        
        // Hide the line
        lineGroup.classList.add('hidden');
        
        // Update button state
        updateAddButtonState();
    }
}

// Function to update add button state
function updateAddButtonState() {
    const container = document.getElementById('kop-surat-container');
    if (!container) return;
    
    const hiddenLines = container.querySelectorAll('.kop-line-group.hidden');
    const addButton = document.getElementById('add-kop-line-btn');
    
    if (addButton) {
        if (hiddenLines.length === 0) {
            addButton.style.display = 'none';
        } else {
            addButton.style.display = 'inline-flex';
        }
    }
}

// Initialize page functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get data passed from Blade template
    const dataElement = document.querySelector('[data-window-var="userEditData"]');
    
    let userKelurahan = [];
    let user = {};
    let authUserRole = '';
    let kopSuratPreviewRoute = '';
    
    if (dataElement) {
        try {
            // Parse JSON data dari attribute
            const userKelurahanJson = dataElement.getAttribute('data-user-kelurahan-json');
            if (userKelurahanJson) {
                userKelurahan = JSON.parse(userKelurahanJson);
            }
            
            // Ambil data user
            user = {
                kelurahan: dataElement.getAttribute('data-user-kelurahan') || '',
                kecamatan: dataElement.getAttribute('data-user-kecamatan') || ''
            };
            
            authUserRole = dataElement.getAttribute('data-auth-user-role') || '';
            kopSuratPreviewRoute = dataElement.getAttribute('data-kop-surat-preview-route') || '';
            
            // Store ke window untuk diakses fungsi lain
            window.userEditData = {
                userKelurahan: userKelurahan,
                user: user,
                authUserRole: authUserRole,
                kopSuratPreviewRoute: kopSuratPreviewRoute
            };
            
            // console.log('User data loaded:', {
            //     userKelurahan: userKelurahan,
            //     user: user,
            //     authUserRole: authUserRole
            // });
        } catch (error) {
            console.error('Error parsing user data:', error);
        }
    }
    
    // Wait for the getDistrictsAndVillages.js to initialize
    setTimeout(() => {
        // Event handler untuk perubahan kecamatan (superadmin edit admin)
        const kecSelect = document.getElementById('kec');
        if (kecSelect) {
            kecSelect.removeEventListener('change', handleKecamatanChange);
            kecSelect.addEventListener('change', handleKecamatanChange);
        }
        
        // Event handler untuk perubahan kecamatan (admin edit sendiri)
        const kecAdminSelect = document.getElementById('kec-admin');
        if (kecAdminSelect) {
            kecAdminSelect.removeEventListener('change', handleKecamatanChangeAdmin);
            kecAdminSelect.addEventListener('change', handleKecamatanChangeAdmin);
        }
        
        // Add event listener for kelurahan change to update other dropdowns
        document.removeEventListener('change', handleKelurahanChange);
        document.addEventListener('change', handleKelurahanChange);
        
        document.removeEventListener('change', handleKelurahanChangeAdmin);
        document.addEventListener('change', handleKelurahanChangeAdmin);
        
        // Initialize existing user data if available
        if (user && user.kelurahan && user.kecamatan) {
            // console.log('Loading single kelurahan data:', user);
            setTimeout(() => {
                if (authUserRole === "SUPERADMIN") {
                    findAndSetKecamatanByKelurahan(user.kelurahan, user.kecamatan);
                } else {
                    findAndSetKecamatanByKelurahanAdmin(user.kelurahan, user.kecamatan);
                }
            }, 2000);
        } else if (userKelurahan && userKelurahan.length > 0) {
            // console.log('Loading multiple kelurahan data:', userKelurahan);
            setTimeout(() => {
                if (authUserRole === "SUPERADMIN") {
                    findAndSetMultipleKelurahan(userKelurahan);
                } else {
                    findAndSetMultipleKelurahanAdmin(userKelurahan);
                }
            }, 2000);
        } else {
            // console.log('No kelurahan data to load');
        }
    }, 1000);
    
    // Initialize role toggle
    toggleKelurahanFieldEdit();
    
    // Initialize kop surat button state
    updateAddButtonState();
    
    // Add role change listener
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleKelurahanFieldEdit);
    }
});

// Event handlers
function handleKecamatanChange(event) {
    const selectedKecamatan = event.target.value;
    const hiddenInput = document.getElementById('selected-kecamatan');
    if (hiddenInput) {
        hiddenInput.value = selectedKecamatan;
    }
    // Tidak perlu call updateKelurahanOptionsEdit karena getDistrictsAndVillages.js sudah handle
}

function handleKecamatanChangeAdmin(event) {
    const selectedKecamatan = event.target.value;
    const hiddenInput = document.getElementById('selected-kecamatan-admin');
    if (hiddenInput) {
        hiddenInput.value = selectedKecamatan;
    }
    // Tidak perlu call updateKelurahanOptionsEditAdmin karena getDistrictsAndVillages.js sudah handle
}

function handleKelurahanChange(event) {
    if (event.target.classList.contains('kelurahan-select')) {
        // Hanya check duplikasi, tidak update semua dropdown
        checkKelurahanDuplicatesEdit();
    }
}

function handleKelurahanChangeAdmin(event) {
    if (event.target.classList.contains('kelurahan-select-admin')) {
        // Hanya check duplikasi, tidak update semua dropdown
        checkKelurahanDuplicatesEditAdmin();
    }
}

// Make functions globally available
window.toggleKelurahanFieldEdit = toggleKelurahanFieldEdit;
window.addKelurahanFieldEdit = addKelurahanFieldEdit;
window.removeKelurahanFieldEdit = removeKelurahanFieldEdit;
window.addKelurahanFieldEditAdmin = addKelurahanFieldEditAdmin;
window.removeKelurahanFieldEditAdmin = removeKelurahanFieldEditAdmin;
window.previewKopSurat = previewKopSurat;
window.addKopLine = addKopLine;
window.removeKopLine = removeKopLine;