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
        const script = document.createElement('script');
        script.src = 'https://sdk.scdn.co/spotify-player.js';
        script.async = true;
        document.body.appendChild(script);

        window.onSpotifyWebPlaybackSDKReady = () => {
          console.log('Spotify Web Playback SDK ready');
          const player = new window.Spotify.Player({
            name: 'El Arquitecto A.I. Web Player',
            getOAuthToken: (cb: SpotifyPlayerCallback) => cb(this.accessToken!),
            volume: this.volume
          });

          player.addListener('ready', ({ device_id }: SpotifyDeviceEvent) => {
            console.log('Spotify player ready with device ID:', device_id);
            this.setDeviceId(device_id);
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
              paused: state.paused
            });

            this.setCurrentTrack(state.track_window.current_track);
            this.setPlayingState(!state.paused);
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
            this.setError(`Playback error: ${message}`);
          });

          console.log('Connecting to Spotify player...');
          player.connect().then((success: boolean) => {
            if (success) {
              console.log('Successfully connected to Spotify player');
            } else {
              console.error('Failed to connect to Spotify player');
              this.setError('Failed to connect to Spotify player');
            }
          });

          this.setPlayer(player);
        };
      } catch (error) {
        this.setError('Failed to initialize Spotify player');
        console.error('Error initializing Spotify player:', error);
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