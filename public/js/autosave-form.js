/**
 * Auto-save form functionality untuk mobile compatibility
 * Mencegah kehilangan data ketika berpindah aplikasi di mobile
 */

class AutoSaveForm {
    constructor(options = {}) {
        this.formSelector = options.formSelector || 'form';
        this.saveInterval = options.saveInterval || 3000; // 3 detik
        this.storageKey = options.storageKey || 'autosave_form_data';
        this.excludeFields = options.excludeFields || ['_token', '_method'];
        this.onSave = options.onSave || null;
        this.onRestore = options.onRestore || null;
        
        this.form = null;
        this.saveTimeout = null;
        this.lastSaveData = '';
        
        this.init();
    }
    
    init() {
        $(document).ready(() => {
            this.form = $(this.formSelector).first();
            if (this.form.length) {
                this.setupEventListeners();
                this.restoreFormData();
                this.showRestoreNotification();
            }
        });
    }
    
    setupEventListeners() {
        // Auto-save saat input berubah
        this.form.on('input change', 'input, textarea, select', () => {
            this.scheduleAutoSave();
        });
        
        // Auto-save saat radio button/checkbox berubah
        this.form.on('change', 'input[type="radio"], input[type="checkbox"]', () => {
            this.scheduleAutoSave();
        });
        
        // Hapus data saat form berhasil disubmit
        this.form.on('submit', () => {
            this.clearSavedData();
        });
        
        // Save saat halaman akan ditutup/refresh
        $(window).on('beforeunload', () => {
            this.saveFormData();
        });
        
        // Save saat visibility berubah (mobile app switch)
        $(document).on('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                this.saveFormData();
            }
        });
        
        // Save saat window kehilangan focus (mobile compatibility)
        $(window).on('blur', () => {
            this.saveFormData();
        });
        
        // Save saat halaman di-pause (mobile)
        $(window).on('pagehide', () => {
            this.saveFormData();
        });
    }
    
    scheduleAutoSave() {
        if (this.saveTimeout) {
            clearTimeout(this.saveTimeout);
        }
        
        this.saveTimeout = setTimeout(() => {
            this.saveFormData();
        }, this.saveInterval);
    }
    
    saveFormData() {
        if (!this.form || !this.form.length) return;
        
        const formData = this.serializeFormData();
        const dataString = JSON.stringify(formData);
        
        // Jangan save jika data tidak berubah
        if (dataString === this.lastSaveData) return;
        
        try {
            localStorage.setItem(this.storageKey, dataString);
            localStorage.setItem(this.storageKey + '_timestamp', Date.now().toString());
            this.lastSaveData = dataString;
            
            if (this.onSave) {
                this.onSave(formData);
            }
            
            console.log('Form data auto-saved');
        } catch (error) {
            console.error('Error saving form data:', error);
        }
    }
    
    restoreFormData() {
        try {
            const savedData = localStorage.getItem(this.storageKey);
            const timestamp = localStorage.getItem(this.storageKey + '_timestamp');
            
            if (!savedData || !timestamp) return;
            
            // Cek apakah data tidak terlalu lama (24 jam)
            const saveTime = parseInt(timestamp);
            const currentTime = Date.now();
            const maxAge = 24 * 60 * 60 * 1000; // 24 jam
            
            if (currentTime - saveTime > maxAge) {
                this.clearSavedData();
                return;
            }
            
            const formData = JSON.parse(savedData);
            this.populateForm(formData);
            
            if (this.onRestore) {
                this.onRestore(formData);
            }
            
            console.log('Form data restored from auto-save');
            return true;
            
        } catch (error) {
            console.error('Error restoring form data:', error);
            this.clearSavedData();
            return false;
        }
    }
    
    serializeFormData() {
        const formData = {};
        const formArray = this.form.serializeArray();
        
        // Serialize form inputs
        formArray.forEach(item => {
            if (!this.excludeFields.includes(item.name)) {
                formData[item.name] = item.value;
            }
        });
        
        // Handle checkboxes yang tidak terpilih
        this.form.find('input[type="checkbox"]').each(function() {
            const name = $(this).attr('name');
            if (name && !this.excludeFields.includes(name)) {
                if (!formData.hasOwnProperty(name)) {
                    formData[name] = '';
                }
            }
        });
        
        // Handle radio buttons
        this.form.find('input[type="radio"]:checked').each(function() {
            const name = $(this).attr('name');
            const value = $(this).val();
            if (name && !this.excludeFields.includes(name)) {
                formData[name] = value;
            }
        });
        
        return formData;
    }
    
    populateForm(formData) {
        Object.keys(formData).forEach(name => {
            const value = formData[name];
            const field = this.form.find(`[name="${name}"]`);
            
            if (field.length) {
                if (field.is('input[type="radio"]')) {
                    field.filter(`[value="${value}"]`).prop('checked', true);
                } else if (field.is('input[type="checkbox"]')) {
                    if (value) {
                        field.prop('checked', true);
                    }
                } else if (field.is('select')) {
                    field.val(value);
                    // Trigger change untuk dependent dropdowns
                    if (value) {
                        field.trigger('change');
                    }
                } else {
                    field.val(value);
                }
            }
        });
    }
    
    showRestoreNotification() {
        const savedData = localStorage.getItem(this.storageKey);
        if (savedData) {
            // Tampilkan notifikasi bahwa data dipulihkan
            this.showToast('Data form dipulihkan dari penyimpanan otomatis', 'info');
        }
    }
    
    showToast(message, type = 'info') {
        // Buat toast notification
        const toastId = 'autosave-toast-' + Date.now();
        const toastClass = type === 'info' ? 'alert-info' : 'alert-success';
        
        const toast = $(`
            <div id="${toastId}" class="alert ${toastClass} fixed top-0 right-0 m-5 w-fit animate-fade-in z-30">
                <i class="ri-information-line"></i>
                <span>${message}</span>
                <button type="button" class="btn btn-ghost btn-square btn-sm close-toast">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `);
        
        $('body').append(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.fadeOut(() => toast.remove());
        }, 5000);
        
        // Manual close
        toast.find('.close-toast').on('click', () => {
            toast.fadeOut(() => toast.remove());
        });
    }
    
    clearSavedData() {
        try {
            localStorage.removeItem(this.storageKey);
            localStorage.removeItem(this.storageKey + '_timestamp');
            this.lastSaveData = '';
            console.log('Auto-save data cleared');
        } catch (error) {
            console.error('Error clearing saved data:', error);
        }
    }
    
    // Method untuk manual save/restore
    manualSave() {
        this.saveFormData();
    }
    
    manualRestore() {
        return this.restoreFormData();
    }
    
    // Method untuk cek apakah ada data tersimpan
    hasSavedData() {
        return !!localStorage.getItem(this.storageKey);
    }
}

// Initialize auto-save untuk form inspection
$(document).ready(function() {
    // Cek apakah halaman ini memiliki form inspection
    if ($('form[action*="store"], form[action*="update"]').length) {
        
        // Ambil route name untuk storage key yang unik
        const currentPath = window.location.pathname;
        const storageKey = 'autosave_form_' + currentPath.replace(/\//g, '_');
        
        window.autoSaveForm = new AutoSaveForm({
            formSelector: 'form[action*="store"], form[action*="update"]',
            saveInterval: 2000, // 2 detik
            storageKey: storageKey,
            excludeFields: ['_token', '_method', 'dokumen_slhs'], // Exclude file uploads
            onSave: function(data) {
                // Optional: tampilkan indikator saving
                console.log('Auto-saved:', Object.keys(data).length, 'fields');
            },
            onRestore: function(data) {
                // Optional: trigger events setelah restore
                console.log('Restored:', Object.keys(data).length, 'fields');
                
                // Trigger events untuk dependent dropdowns
                setTimeout(() => {
                    $('#kec').trigger('change.kecamatan');
                }, 500);
            }
        });
    }
});

// Global functions untuk debugging
window.clearAutoSave = function() {
    if (window.autoSaveForm) {
        window.autoSaveForm.clearSavedData();
        console.log('Auto-save data cleared manually');
    }
};

window.checkAutoSave = function() {
    if (window.autoSaveForm) {
        const hasSaved = window.autoSaveForm.hasSavedData();
        console.log('Has saved data:', hasSaved);
        return hasSaved;
    }
    return false;
};

window.manualSaveForm = function() {
    if (window.autoSaveForm) {
        window.autoSaveForm.manualSave();
        console.log('Manual save triggered');
    }
};

window.manualRestoreForm = function() {
    if (window.autoSaveForm) {
        const restored = window.autoSaveForm.manualRestore();
        console.log('Manual restore triggered:', restored);
        return restored;
    }
    return false;
};