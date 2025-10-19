/**
 * Sekolah Copy Error Details
 * Handles copying error details to clipboard
 */

function copyErrorDetails() {
    const textarea = document.getElementById('errorDetails');
    
    if (!textarea) {
        return;
    }
    
    textarea.select();
    textarea.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.remove('bg-red-600', 'hover:bg-red-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-red-600', 'hover:bg-red-700');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Gagal meng-copy. Silakan copy manual.');
    }
}
