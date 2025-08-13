import CookieConsentService from '../cookie-consent';

/**
 * Initialize cookie consent functionality
 * This file can be imported as a module in your app
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize the cookie consent service
    const cookieConsent = new CookieConsentService();
    
    // Make it available globally for debugging
    window.cookieConsent = cookieConsent;
    
    // Check for existing cookie consent and act accordingly
    if (cookieConsent.hasConsentChoice()) {
        if (cookieConsent.areCookiesAccepted()) {
            cookieConsent.enableCookies();
        } else {
            cookieConsent.disableCookies();
        }
    }
    
    // Optional: Add a function to reset cookie consent (for testing)
    window.resetCookieConsent = () => {
        cookieConsent.resetConsentChoice();
        location.reload();
    };
});

// Export the CookieConsentService for use in other components
export { CookieConsentService }; 