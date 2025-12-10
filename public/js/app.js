// AbleLink - Plain JavaScript (No Node.js dependencies)

// Auto-submit OTP form when 6 digits are entered
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp_code');
    
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Auto-submit when 6 digits are entered
            if (e.target.value.length === 6) {
                const form = e.target.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
        
        // Focus the OTP input on page load
        otpInput.focus();
    }
    
    // Enhance form accessibility: announce errors to screen readers
    const errorAlerts = document.querySelectorAll('.alert-error');
    errorAlerts.forEach(function(alert) {
        alert.setAttribute('role', 'alert');
        alert.setAttribute('aria-live', 'assertive');
    });
    
    // Enhance success messages
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(function(alert) {
        alert.setAttribute('role', 'status');
        alert.setAttribute('aria-live', 'polite');
    });
    
    // Keyboard navigation enhancement for radio groups
    const radioGroups = document.querySelectorAll('.radio-group');
    radioGroups.forEach(function(group) {
        const radios = group.querySelectorAll('input[type="radio"]');
        radios.forEach(function(radio, index) {
            radio.addEventListener('keydown', function(e) {
                let nextIndex;
                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextIndex = (index + 1) % radios.length;
                    radios[nextIndex].focus();
                    radios[nextIndex].checked = true;
                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    nextIndex = (index - 1 + radios.length) % radios.length;
                    radios[nextIndex].focus();
                    radios[nextIndex].checked = true;
                }
            });
        });
    });
});

// Screen reader announcement helper
function announceToScreenReader(message, priority) {
    const announcement = document.createElement('div');
    announcement.setAttribute('role', priority === 'assertive' ? 'alert' : 'status');
    announcement.setAttribute('aria-live', priority || 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = message;
    document.body.appendChild(announcement);
    
    setTimeout(function() {
        document.body.removeChild(announcement);
    }, 1000);
}
