/**
 * Spotify API service
 * Provides utility functions for interacting with the Spotify API
 */

/**
 * Play a specific playlist on the active device
 * @param token Access token
 * @param deviceId Spotify device ID
 * @param playlistUri Spotify playlist URI
 */
export async function playPlaylist(token: string, deviceId: string, playlistUri: string): Promise<void> {
  try {
    // Extract playlist ID from URI (format: spotify:playlist:37i9dQZF1DXdLEN7aqioXM)
    const playlistId = playlistUri.split(':').pop();

    const response = await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        context_uri: `spotify:playlist:${playlistId}`,
      }),
    });

    if (!response.ok && response.status !== 204) {
      const error = await response.json();
      throw new Error(error.error.message || 'Failed to play playlist');
    }
  } catch (error) {
    console.error('Error playing playlist:', error);
    throw error;
  }
}

/**
 * Get the default playlist URI from the backend
 */
export async function getDefaultPlaylist(): Promise<string> {
  try {
    const response = await fetch('/spotify/default-playlist');
    const data = await response.json();
    return data.playlist_uri;
  } catch (error) {
    console.error('Error fetching default playlist:', error);
    // Fallback to a default synthwave playlist
    return 'spotify:playlist:37i9dQZF1DXdLEN7aqioXM';
  }
}

/**
 * Get user profile information
 * @param token Access token
 */
export async function getUserProfile(token: string): Promise<any> {
  try {
    const response = await fetch('https://api.spotify.com/v1/me', {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });

    if (!response.ok) {
      throw new Error('Failed to fetch user profile');
    }

    return await response.json();
  } catch (error) {
    console.error('Error fetching user profile:', error);
    throw error;
  }
}

/**
 * Check if the current token is a user token or a client credentials token
 * @param token Access token
 */
export async function isUserToken(token: string): Promise<boolean> {
  try {
    console.log('Checking if token is a user token...');

    // First, check if we can access user profile - this is the most reliable method
    try {
      await getUserProfile(token);
      console.log('Successfully retrieved user profile, token is a user token');
      return true;
    } catch (profileError) {
      console.log('Failed to retrieve user profile:', profileError);

      // If profile access fails, check token response
      const response = await fetch('/spotify/token');
      const data = await response.json();

      console.log('Token response:', {
        isDefault: data.is_default || false,
        hasRefreshToken: !!data.refresh_token
      });

      // If we have a refresh token or it's explicitly not a default token, it's a user token
      if (data.refresh_token || (data.is_default === false)) {
        return true;
      }

      return false;
    }
  } catch (error) {
    console.error('Error checking if token is user token:', error);
    return false;
  }
}