/**
 * Kop Surat Preview HTML - Auto close functionality
 */

// Auto close after 30 seconds if opened in popup
if (window.opener) {
    setTimeout(function() {
        if (confirm('Tutup jendela preview ini?')) {
            window.close();
        }
    }, 30000);
}
