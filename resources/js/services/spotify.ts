/**
 * Spotify API service
 * Provides utility functions for interacting with the Spotify API
 */

import config from '@/config';

/**
 * Get the default playlist URI from config
 */
export function getDefaultPlaylist(): string {
  return config.spotify.defaultPlaylist;
}

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

    if (!playlistId) {
      throw new Error('Invalid playlist URI');
    }

    // First check if we can play on this device
    const deviceResponse = await fetch(`https://api.spotify.com/v1/me/player/devices`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });

    // Check for 401/403 errors which might indicate a client credentials token or non-premium account
    if (deviceResponse.status === 401 || deviceResponse.status === 403) {
      const errorData = await deviceResponse.json();
      console.error('Authorization error getting devices:', errorData);

      if (errorData.error?.message?.includes('premium')) {
        throw new Error('Premium required for playback');
      } else {
        throw new Error(`Authorization error: ${errorData.error?.message || 'Unauthorized'}`);
      }
    }

    if (!deviceResponse.ok) {
      const errorData = await deviceResponse.json();
      console.error('Failed to get devices:', errorData);
      throw new Error(`Failed to get devices: ${errorData.error?.message || 'Unknown error'}`);
    }

    const devices = await deviceResponse.json();
    const targetDevice = devices.devices.find((d: any) => d.id === deviceId);

    if (!targetDevice) {
      throw new Error('Device not found');
    }

    if (!targetDevice.is_active) {
      // Transfer playback to our device first
      const transferResponse = await fetch('https://api.spotify.com/v1/me/player', {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          device_ids: [deviceId],
          play: false,
        }),
      });

      // Check for errors in transfer response
      if (!transferResponse.ok && transferResponse.status !== 204) {
        const errorData = await transferResponse.json().catch(() => ({}));
        console.error('Error transferring playback:', errorData);

        if (errorData.error?.message?.includes('premium')) {
          throw new Error('Premium required for playback');
        } else if (transferResponse.status === 401 || transferResponse.status === 403) {
          throw new Error('Authorization error: Not authorized to transfer playback');
        } else {
          throw new Error(`Failed to transfer playback: ${errorData.error?.message || 'Unknown error'}`);
        }
      }

      // Wait a moment for the transfer to complete
      await new Promise(resolve => setTimeout(resolve, 1000));
    }

    // Now try to play the playlist
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
      const errorData = await response.json().catch(() => ({}));
      console.error('Spotify play error:', errorData);

      if (errorData.error?.message?.includes('premium')) {
        throw new Error('Premium required for playback');
      } else if (response.status === 401 || response.status === 403) {
        throw new Error('Authorization error: Not authorized to play content');
      } else {
        throw new Error(errorData.error?.message || 'Failed to play playlist');
      }
    }
  } catch (error) {
    console.error('Error playing playlist:', error);
    throw error;
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