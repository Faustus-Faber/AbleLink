// AbleLink - Plain JavaScript Bootstrap (No Node.js dependencies)

// Simple fetch wrapper for AJAX requests (replaces axios)
window.ableLinkFetch = function(url, options = {}) {
    const defaultOptions = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        },
        ...options
    };
    
    return fetch(url, defaultOptions);
};

// CSRF token helper
window.getCsrfToken = function() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
};

// Add CSRF token to all fetch requests automatically
const originalFetch = window.fetch;
window.fetch = function(url, options = {}) {
    const csrfToken = window.getCsrfToken();
    if (csrfToken && !options.headers) {
        options.headers = {};
    }
    if (csrfToken && options.headers) {
        options.headers['X-CSRF-TOKEN'] = csrfToken;
    }
    return originalFetch(url, options);
};
