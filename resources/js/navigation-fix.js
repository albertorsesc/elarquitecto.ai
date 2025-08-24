// Prevent white flash on navigation
(function() {
    // Set dark background immediately
    document.documentElement.style.backgroundColor = '#0a0a0a';
    document.body.style.backgroundColor = '#0a0a0a';
    
    // Add loading state for all navigation
    document.addEventListener('DOMContentLoaded', function() {
        // Get all links
        const links = document.querySelectorAll('a[href^="/"]');
        
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Skip if it's a download or external link
                if (link.hasAttribute('download') || link.hasAttribute('target')) {
                    return;
                }
                
                // Skip if it's a hash link
                if (link.getAttribute('href').startsWith('#')) {
                    return;
                }
                
                // Add loading class to body
                document.body.classList.add('page-loading');
                
                // Create or show loading overlay
                let loadingOverlay = document.getElementById('loading-overlay');
                if (!loadingOverlay) {
                    loadingOverlay = document.createElement('div');
                    loadingOverlay.id = 'loading-overlay';
                    loadingOverlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: #0a0a0a;
                        z-index: 9999;
                        opacity: 0;
                        transition: opacity 0.2s;
                        pointer-events: none;
                    `;
                    document.body.appendChild(loadingOverlay);
                }
                
                // Fade in the overlay
                setTimeout(() => {
                    loadingOverlay.style.opacity = '1';
                }, 10);
            });
        });
    });
    
    // Hide loading overlay when page is fully loaded
    window.addEventListener('pageshow', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            setTimeout(() => {
                loadingOverlay.remove();
            }, 200);
        }
        document.body.classList.remove('page-loading');
    });
})();