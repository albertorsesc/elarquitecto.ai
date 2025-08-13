/**
 * Cookie Consent Service
 * Provides functionality for managing cookie consent
 */
export default class CookieConsentService {
    constructor() {
        this.storageKey = 'cookie-consent-choice';
        this.initEventListeners();
    }

    /**
     * Initialize event listeners for cookie consent updates
     */
    initEventListeners() {
        window.addEventListener('cookie-consent-updated', (event) => {
            const { choice } = event.detail;
            
            if (choice === 'accepted') {
                this.enableCookies();
            } else {
                this.disableCookies();
            }
        });
    }

    /**
     * Check if user has already made a cookie choice
     * @returns {boolean} True if user has made a choice
     */
    hasConsentChoice() {
        return localStorage.getItem(this.storageKey) !== null;
    }

    /**
     * Get the current cookie consent choice
     * @returns {string|null} 'accepted', 'rejected' or null if no choice made
     */
    getConsentChoice() {
        return localStorage.getItem(this.storageKey);
    }

    /**
     * Check if cookies are accepted
     * @returns {boolean} True if cookies are accepted
     */
    areCookiesAccepted() {
        return localStorage.getItem(this.storageKey) === 'accepted';
    }

    /**
     * Enable cookies - implement cookie setting functionality here
     * This is where you would initialize analytics, etc.
     */
    enableCookies() {
        console.log('Cookies enabled');
        
        // Enable Google Analytics
        if (typeof gtag === 'function') {
            window['ga-disable-G-7NGTTSRYL1'] = false;
        }
        
        // Add other cookie-dependent services here
    }

    /**
     * Disable cookies - implement cookie clearing functionality here
     */
    disableCookies() {
        console.log('Cookies disabled');
        
        // Disable Google Analytics
        if (typeof gtag === 'function') {
            window['ga-disable-G-7NGTTSRYL1'] = true;
        }
        
        // Remove or disable other cookies here
        this.removeCookies();
    }

    /**
     * Remove existing cookies from the browser
     */
    removeCookies() {
        const cookies = document.cookie.split(';');
        
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i];
            const eqPos = cookie.indexOf('=');
            const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
            
            // Skip essential cookies that should remain
            if (name === 'XSRF-TOKEN' || name === 'laravel_session') continue;
            
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
        }
    }

    /**
     * Reset the consent choice (for testing)
     */
    resetConsentChoice() {
        localStorage.removeItem(this.storageKey);
    }
} 