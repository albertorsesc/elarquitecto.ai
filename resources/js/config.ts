/**
 * Application configuration from environment variables
 */
export const config = {
  spotify: {
    clientId: import.meta.env.VITE_SPOTIFY_CLIENT_ID || '',
    enabled: import.meta.env.VITE_SPOTIFY_ENABLED === 'true',
    defaultPlaylist: import.meta.env.VITE_SPOTIFY_DEFAULT_PLAYLIST || 'spotify:playlist:37i9dQZF1DXcBWIGoYBM5M'
  }
};

export default config;