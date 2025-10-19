// Add User Modal JavaScript Functions

// Toggle kelurahan field visibility based on role selection
function toggleKelurahanField() {
    const roleSelect = document.getElementById('role');
    const kelurahanField = document.getElementById('kelurahan-field');
    const kelurahanInputField = document.getElementById('kelurahan-input-field');
    
    if (roleSelect && kelurahanField && kelurahanInputField) {
        if (roleSelect.value === 'USER' || roleSelect.value === 'ADMIN') {
            kelurahanField.style.display = 'block';
            kelurahanInputField.style.display = 'block';
        } else {
            kelurahanField.style.display = 'none';
            kelurahanInputField.style.display = 'none';
            
            // Reset kelurahan container when hiding
            resetKelurahanContainer();
        }
    }
}

// Add new kelurahan field
function addKelurahanField() {
    const container = document.getElementById('kelurahan-container');
    if (!container) return;
    
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
    
    const kelurahanRow = document.createElement('div');
    kelurahanRow.className = 'kelurahan-row flex gap-2 mb-2';
    kelurahanRow.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
            ${optionsHTML}
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanField(this)">-</button>
    `;
    container.appendChild(kelurahanRow);
    
    // Add event listener untuk select yang baru dibuat
    const newSelect = kelurahanRow.querySelector('.kelurahan-select');
    if (newSelect) {
        newSelect.addEventListener('change', function() {
            updateKelurahanOptionsModal();
            checkKelurahanDuplicates();
        });
    }
    
    // Update semua dropdown untuk hide kelurahan yang sudah dipilih
    updateKelurahanOptionsModal();
    
    // Cek duplikasi setelah menambah field
    checkKelurahanDuplicates();
}

// Remove kelurahan field
function removeKelurahanField(button) {
    const container = document.getElementById('kelurahan-container');
    if (!container || container.children.length <= 1) return;
    
    button.closest('.kelurahan-row').remove();
    
    // Update opsi untuk semua select kelurahan
    updateKelurahanOptionsModal();
    
    // Cek duplikasi setelah menghapus field
    checkKelurahanDuplicates();
}

// Reset kelurahan container to initial state
function resetKelurahanContainer() {
    const container = document.getElementById('kelurahan-container');
    container.innerHTML = `
        <div class="kelurahan-row flex gap-2 mb-2">
            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select" required>
                <option value="">Pilih Kelurahan</option>
            </select>
            <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanField()">+</button>
        </div>
    `;
}

// Update kelurahan options for all select elements in modal
function updateKelurahanOptionsModal() {
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select');
    if (kelurahanSelects.length === 0) return;
    
    // Get all selected kelurahan values
    const selectedKelurahan = [];
    kelurahanSelects.forEach(select => {
        if (select.value && select.value !== '') {
            selectedKelurahan.push(select.value);
        }
    });
    
    console.log('Selected kelurahan in modal:', selectedKelurahan);
    
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
    
    // Check for duplicates and show warning
    checkKelurahanDuplicates();
}

// Legacy function for backward compatibility
function updateKelurahanOptions() {
    updateKelurahanOptionsModal();
}

// Check for duplicate kelurahan selections
function checkKelurahanDuplicates() {
    const selectedValues = [];
    const warningDiv = document.getElementById('kelurahan-duplicate-warning');
    const submitButton = document.querySelector('button[type="submit"]');
    
    document.querySelectorAll('.kelurahan-select').forEach(select => {
        if (select.value && select.value !== '') {
            selectedValues.push(select.value);
        }
    });
    
    // Check for duplicates
    const hasDuplicates = selectedValues.length !== new Set(selectedValues).size;
    
    if (hasDuplicates) {
        warningDiv.style.display = 'block';
        submitButton.disabled = true;
        submitButton.classList.add('btn-disabled');
    } else {
        warningDiv.style.display = 'none';
        submitButton.disabled = false;
        submitButton.classList.remove('btn-disabled');
    }
}

// Preview kop surat from modal
function previewKopSuratModal() {
    // Ambil data dari semua baris kop surat (1-10) di modal
    const modal = document.getElementById('add_user');
    const formData = {};
    
    for (let i = 1; i <= 10; i++) {
        const sizeInput = modal.querySelector(`#sizebaris${i}`);
        const textInput = modal.querySelector(`#baris${i}`);
        
        if (sizeInput && textInput) {
            formData[`sizebaris${i}`] = sizeInput.value || '13px';
            formData[`baris${i}`] = textInput.value || '';
        }
    }
    
    // Buat URL dengan parameter
    const params = new URLSearchParams(formData);
    const url = `${window.location.origin}/preview-kop-surat-pdf?${params.toString()}`;
    
    // Buka PDF di tab baru
    window.open(url, '_blank');
}

// Add new kop surat line in modal
function addKopLineModal() {
    const roleSelect = document.getElementById('role');
    const currentRole = roleSelect ? roleSelect.value : 'ADMIN';
    const maxLines = (currentRole === 'SUPERADMIN') ? 4 : 10;
    
    const container = document.getElementById('kop-surat-container-modal');
    const hiddenLines = container.querySelectorAll('.kop-line-group-modal.hidden');
    const visibleLines = container.querySelectorAll('.kop-line-group-modal:not(.hidden)');
    
    if (hiddenLines.length > 0 && visibleLines.length < maxLines) {
        // Ambil baris tersembunyi pertama dan tampilkan
        const nextLine = hiddenLines[0];
        nextLine.classList.remove('hidden');
        
        // Update button state
        updateAddButtonStateModal();
    }
}

// Remove kop surat line in modal
function removeKopLineModal(lineNumber) {
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
        updateAddButtonStateModal();
    }
}

// Update add button state in modal
function updateAddButtonStateModal() {
    const roleSelect = document.getElementById('role');
    const currentRole = roleSelect ? roleSelect.value : 'ADMIN';
    const maxLines = (currentRole === 'SUPERADMIN') ? 4 : 10;
    
    const container = document.getElementById('kop-surat-container-modal');
    const visibleLines = container.querySelectorAll('.kop-line-group-modal:not(.hidden)');
    const hiddenLines = container.querySelectorAll('.kop-line-group-modal.hidden');
    const addButton = document.getElementById('add-kop-line-btn-modal');
    
    // Hide add button if max lines reached or no more hidden lines
    if (visibleLines.length >= maxLines || hiddenLines.length === 0) {
        addButton.style.display = 'none';
    } else {
        addButton.style.display = 'inline-flex';
    }
    
    // Update limit text
    const limitText = document.getElementById('kop-limit-text');
    if (limitText) {
        limitText.textContent = `Maksimal ${maxLines} baris`;
    }
}

// Initialize modal state when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for role change
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            const maxLines = (selectedRole === 'SUPERADMIN') ? 4 : 10;
            
            // Toggle kelurahan field visibility
            toggleKelurahanField();
            
            // Hide lines beyond the max for the new role
            const container = document.getElementById('kop-surat-container-modal');
            const allLines = container.querySelectorAll('.kop-line-group-modal');
            
            allLines.forEach((line, index) => {
                if (index >= maxLines) {
                    // Clear inputs and hide line
                    const inputs = line.querySelectorAll('input');
                    inputs.forEach(input => input.value = '');
                    line.classList.add('hidden');
                } else if (index < 1) {
                    // Always show first line
                    line.classList.remove('hidden');
                }
            });
            
            updateAddButtonStateModal();
        });
    }
    
    updateAddButtonStateModal();
    
    // Add event listener for kecamatan change (requires jQuery)
    if (typeof $ !== 'undefined') {
        $('#kec').on('change', function() {
            const selectedKecamatan = $(this).val();
            $('#selected-kecamatan').val(selectedKecamatan);
            
            // Update all kelurahan options when kecamatan changes
            setTimeout(() => {
                updateKelurahanOptionsModal();
            }, 1500);
        });
    }
    
    // Add event listener untuk semua kelurahan select yang sudah ada
    document.querySelectorAll('.kelurahan-select').forEach(select => {
        select.addEventListener('change', function() {
            updateKelurahanOptionsModal();
            checkKelurahanDuplicates();
        });
    });
    
    // Add event listener for add kelurahan button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-kelurahan-btn') || 
            e.target.closest('.add-kelurahan-btn')) {
            e.preventDefault();
            addKelurahanField();
        }
    });
});

// Make functions globally available for onclick handlers
window.addKelurahanField = addKelurahanField;
window.removeKelurahanField = removeKelurahanField;
window.addKopLineModal = addKopLineModal;
window.removeKopLineModal = removeKopLineModal;
window.previewKopSuratModal = previewKopSuratModal;