import config from '@/config';
import { SpotifyApi } from '@spotify/web-api-ts-sdk';

/**
 * Spotify authentication service
 * Provides methods for authenticating with Spotify and managing tokens
 */
export const useSpotifyAuth = () => {
  console.log('Initializing Spotify Auth with config:', {
    clientId: config.spotify.clientId,
    redirectUri: window.location.origin + '/spotify/callback'
  });

  // Create a new Spotify API instance with user authorization
  const sdk = SpotifyApi.withUserAuthorization(
    config.spotify.clientId,
    window.location.origin + '/spotify/callback',
    [
      'streaming',
      'user-read-email',
      'user-read-private',
      'user-read-playback-state',
      'user-modify-playback-state',
      'user-read-currently-playing',
      'app-remote-control'  // Add this scope for Web Playback SDK
    ]
  );

  /**
   * Initialize token refresh mechanism
   * Checks token expiration every 58.33 minutes and redirects to login if expired
   */
  const initTokenRefresh = () => {
    // Check token every 58.33 minutes (3500000 ms)
    setInterval(async () => {
      try {
        const token = await sdk.getAccessToken();
        console.log('Token check:', {
          hasToken: !!token,
          expires: token?.expires ? new Date(token.expires).toISOString() : 'no expiry',
          now: new Date().toISOString()
        });

        if (token && token.expires && Date.now() >= token.expires) {
          console.log('Token expired, redirecting to login...');
          window.location.href = '/spotify/login';
        }
      } catch (error) {
        console.error('Error checking token:', error);
        window.location.href = '/spotify/login';
      }
    }, 3500000);
  };

  /**
   * Get the current access token
   */
  const getAccessToken = async (): Promise<string> => {
    try {
      console.log('Getting access token...');
      const token = await sdk.getAccessToken();

      if (!token) {
        console.error('No token received from SDK');
        window.location.href = '/spotify/login';
        return '';
      }

      if (!token.access_token) {
        console.error('Token received but no access_token present:', token);
        window.location.href = '/spotify/login';
        return '';
      }

      console.log('Successfully retrieved access token');
      return token.access_token;
    } catch (error) {
      console.error('Error getting access token:', error);
      window.location.href = '/spotify/login';
      return '';
    }
  };

  return {
    sdk,
    initTokenRefresh,
    getAccessToken
  };
};

export default useSpotifyAuth;