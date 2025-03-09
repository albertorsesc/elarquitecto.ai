import { defineStore } from 'pinia';

interface SpotifyState {
  accessToken: string | null;
  isAuthenticated: boolean;
  player: any | null;
  deviceId: string | null;
  currentTrack: any | null;
  isPlaying: boolean;
  volume: number;
  isExpanded: boolean;
  isMinimized: boolean;
  error: string | null;
  playbackPosition: number;
  shuffleEnabled: boolean;
}

interface SpotifyPlayerCallback {
  (token: string): void;
}

interface SpotifyDeviceEvent {
  device_id: string;
}

interface SpotifyPlayerState {
  track_window: {
    current_track: any;
  };
  paused: boolean;
  position: number;
}

export const useSpotifyStore = defineStore('spotify', {
  state: (): SpotifyState => ({
    accessToken: null,
    isAuthenticated: false,
    player: null,
    deviceId: null,
    currentTrack: null,
    isPlaying: false,
    volume: localStorage.getItem('spotify_volume') ? parseFloat(localStorage.getItem('spotify_volume')!) : 0.5,
    isExpanded: localStorage.getItem('spotify_expanded') ? localStorage.getItem('spotify_expanded') === 'true' : true,
    isMinimized: localStorage.getItem('spotify_minimized') ? localStorage.getItem('spotify_minimized') === 'true' : false,
    error: null,
    playbackPosition: 0,
    shuffleEnabled: localStorage.getItem('spotify_shuffle') ? localStorage.getItem('spotify_shuffle') === 'true' : true,
  }),

  getters: {
    isReady: (state: SpotifyState) => state.player !== null && state.deviceId !== null,
    hasError: (state: SpotifyState) => state.error !== null,
  },

  actions: {
    setAccessToken(token: string | null) {
      this.accessToken = token;
      this.isAuthenticated = token !== null;
    },

    setPlayer(player: any) {
      this.player = player;
    },

    setDeviceId(deviceId: string | null) {
      this.deviceId = deviceId;
    },

    setCurrentTrack(track: any | null) {
      this.currentTrack = track;
    },

    setPlayingState(isPlaying: boolean) {
      this.isPlaying = isPlaying;
    },

    setVolume(volume: number) {
      this.volume = volume;
      localStorage.setItem('spotify_volume', volume.toString());
      if (this.player) {
        this.player.setVolume(volume);
      }
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
      localStorage.setItem('spotify_expanded', this.isExpanded.toString());
    },

    toggleMinimized() {
      this.isMinimized = !this.isMinimized;
      localStorage.setItem('spotify_minimized', this.isMinimized.toString());
    },

    setMinimized(value: boolean) {
      this.isMinimized = value;
      localStorage.setItem('spotify_minimized', value.toString());
    },

    setError(error: string | null) {
      this.error = error;
    },

    async initializePlayer() {
      if (!this.accessToken) return;

      try {
        console.log('Initializing Spotify Web Playback SDK');

        // Check if the SDK script is already loaded
        if (!document.getElementById('spotify-player-sdk')) {
          const script = document.createElement('script');
          script.id = 'spotify-player-sdk';
          script.src = 'https://sdk.scdn.co/spotify-player.js';
          script.async = true;
          document.body.appendChild(script);
        }

        // Create a promise that resolves when the SDK is ready
        const sdkReady = new Promise<void>((resolve) => {
          if (window.Spotify && window.Spotify.Player) {
            resolve();
          } else {
            window.onSpotifyWebPlaybackSDKReady = () => {
              resolve();
            };
          }
        });

        // Wait for the SDK to be ready
        await sdkReady;
        console.log('Spotify Web Playback SDK ready');

        // Create the player
        const player = new window.Spotify.Player({
          name: 'El Arquitecto A.I. Web Player',
          getOAuthToken: (cb: SpotifyPlayerCallback) => cb(this.accessToken!),
          volume: this.volume
        });

        // Add event listeners
        player.addListener('ready', ({ device_id }: SpotifyDeviceEvent) => {
          console.log('Spotify player ready with device ID:', device_id);
          this.setDeviceId(device_id);

          // Start playback on the new device
          this.startPlayback(device_id);
        });

        player.addListener('not_ready', ({ device_id }: SpotifyDeviceEvent) => {
          console.log('Device ID has gone offline', device_id);
        });

        player.addListener('player_state_changed', (state: SpotifyPlayerState | null) => {
          if (!state) {
            console.log('Player state changed but state is null');
            return;
          }

          console.log('Player state changed:', {
            track: state.track_window.current_track?.name,
            artist: state.track_window.current_track?.artists?.[0]?.name,
            paused: state.paused,
            position: state.position,
          });

          this.setCurrentTrack(state.track_window.current_track);
          this.setPlayingState(!state.paused);

          // Store the current playback position
          this.playbackPosition = state.position;
        });

        player.addListener('initialization_error', ({ message }: { message: string }) => {
          console.error('Spotify player initialization error:', message);
          this.setError(`Initialization error: ${message}`);
        });

        player.addListener('authentication_error', ({ message }: { message: string }) => {
          console.error('Spotify player authentication error:', message);
          this.setError(`Authentication error: ${message}`);
        });

        player.addListener('account_error', ({ message }: { message: string }) => {
          console.error('Spotify player account error:', message);
          this.setError(`Account error: ${message}`);
        });

        player.addListener('playback_error', ({ message }: { message: string }) => {
          console.error('Spotify player playback error:', message);
          // Don't set error for playback errors as they're often transient
          // this.setError(`Playback error: ${message}`);
        });

        // Connect to Spotify
        const connected = await player.connect();
        if (connected) {
          console.log('Connected to Spotify successfully');
          this.setPlayer(player);
        } else {
          throw new Error('Failed to connect to Spotify');
        }
      } catch (error: any) {
        console.error('Error initializing Spotify player:', error);
        this.setError(`Failed to initialize Spotify player: ${error.message}`);
      }
    },

    // Add a method to start playback on the device
    async startPlayback(deviceId: string) {
      if (!this.accessToken || !deviceId) return;

      try {
        console.log('Starting playback on device:', deviceId);

        // Get a playlist to play
        const playlistResponse = await fetch('/spotify/default-playlist');
        const playlistData = await playlistResponse.json();
        const contextUri = playlistData.playlist_uri || 'spotify:playlist:37i9dQZF1DXdLEN7aqioXM'; // Fallback to a default playlist

        // Set shuffle mode first
        await fetch(`https://api.spotify.com/v1/me/player/shuffle?state=${this.shuffleEnabled}&device_id=${deviceId}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${this.accessToken}`,
          }
        });

        // Start playback
        const response = await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${this.accessToken}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            context_uri: contextUri,
            position_ms: this.playbackPosition > 0 ? this.playbackPosition : 0
          })
        });

        if (!response.ok) {
          // If we get a 403, it might be because the user doesn't have Spotify Premium
          if (response.status === 403) {
            const errorData = await response.json();
            if (errorData.error && errorData.error.reason === 'PREMIUM_REQUIRED') {
              throw new Error('Spotify Premium is required for playback');
            }
          }

          throw new Error(`Failed to start playback: ${response.statusText}`);
        }

        console.log('Playback started successfully');
      } catch (error: any) {
        console.error('Error starting playback:', error);
        this.setError(`Failed to start playback: ${error.message}`);
      }
    },

    // Toggle shuffle mode
    async toggleShuffle() {
      if (!this.accessToken || !this.deviceId) return;

      try {
        this.shuffleEnabled = !this.shuffleEnabled;
        localStorage.setItem('spotify_shuffle', this.shuffleEnabled.toString());

        const response = await fetch(`https://api.spotify.com/v1/me/player/shuffle?state=${this.shuffleEnabled}&device_id=${this.deviceId}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${this.accessToken}`
          }
        });

        if (!response.ok) {
          throw new Error(`Failed to set shuffle mode: ${response.statusText}`);
        }

        console.log(`Shuffle mode ${this.shuffleEnabled ? 'enabled' : 'disabled'}`);
      } catch (error: any) {
        console.error('Error toggling shuffle mode:', error);
        // Revert the local state if the API call failed
        this.shuffleEnabled = !this.shuffleEnabled;
        localStorage.setItem('spotify_shuffle', this.shuffleEnabled.toString());
      }
    },

    async togglePlay() {
      if (!this.player) return;
      try {
        await this.player.togglePlay();
      } catch (error) {
        this.setError('Failed to toggle playback');
        console.error('Error toggling playback:', error);
      }
    },

    async seekTo(position_ms: number) {
      if (!this.player) return;
      try {
        await this.player.seek(position_ms);
      } catch (error) {
        this.setError('Failed to seek');
        console.error('Error seeking:', error);
      }
    },

    async previousTrack() {
      if (!this.player) return;
      try {
        await this.player.previousTrack();
      } catch (error) {
        this.setError('Failed to play previous track');
        console.error('Error playing previous track:', error);
      }
    },

    async nextTrack() {
      if (!this.player) return;
      try {
        await this.player.nextTrack();
      } catch (error) {
        this.setError('Failed to play next track');
        console.error('Error playing next track:', error);
      }
    },

    disconnect() {
      if (this.player) {
        this.player.disconnect();
        this.setPlayer(null);
        this.setDeviceId(null);
        this.setCurrentTrack(null);
        this.setPlayingState(false);
      }
    },
  },
});

// Add type definitions for Spotify SDK
declare global {
  interface Window {
    onSpotifyWebPlaybackSDKReady: () => void;
    Spotify: {
      Player: new (config: any) => any;
    };
  }
}