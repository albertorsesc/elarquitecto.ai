import useSpotifyAuth from '@/services/spotify-auth';
import SpotifyPlayerService from '@/services/spotify-player';
import { defineStore } from 'pinia';

/**
 * Interface for a Spotify track
 */
interface WebPlaybackTrack {
  uri: string;
  id: string | null;
  type: string;
  media_type: string;
  name: string;
  is_playable: boolean;
  album: {
    uri: string;
    name: string;
    images: Array<{ url: string }>;
  };
  artists: Array<{ uri: string; name: string }>;
}

/**
 * Interface for Spotify state
 */
interface SpotifyState {
  // Authentication
  isAuthenticated: boolean;

  // Player
  player: any | null;
  playerService: SpotifyPlayerService | null;
  deviceId: string | null;

  // Playback state
  currentTrack: WebPlaybackTrack | null;
  isPlaying: boolean;
  volume: number;
  playbackPosition: number;
  trackDuration: number;
  shuffleEnabled: boolean;

  // UI state
  isExpanded: boolean;
  isMinimized: boolean;
  error: string | null;

  // Internal state
  isInitialized: boolean;
}

/**
 * Spotify store using Pinia
 */
export const useSpotifyStore = defineStore('spotify', {
  state: (): SpotifyState => ({
    // Authentication
    isAuthenticated: false,

    // Player
    player: null,
    playerService: null,
    deviceId: null,

    // Playback state
    currentTrack: null,
    isPlaying: false,
    volume: localStorage.getItem('spotify_volume') ? parseFloat(localStorage.getItem('spotify_volume')!) : 0.5,
    playbackPosition: 0,
    trackDuration: 0,
    shuffleEnabled: localStorage.getItem('spotify_shuffle') === 'true',

    // UI state
    isExpanded: localStorage.getItem('spotify_expanded') !== 'false',
    isMinimized: localStorage.getItem('spotify_minimized') === 'true',
    error: null,

    // Internal state
    isInitialized: false
  }),

  /**
   * Getters
   */
  getters: {
    isReady: (state) => state.player !== null && state.deviceId !== null,
    hasError: (state) => state.error !== null
  },

  /**
   * Actions
   */
  actions: {
    /**
     * Initialize the Spotify integration
     */
    async initialize() {
      try {
        const { sdk, initTokenRefresh } = useSpotifyAuth();

        // Initialize player service
        const playerService = new SpotifyPlayerService(sdk);
        this.playerService = playerService;

        // Initialize token refresh
        initTokenRefresh();

        // Set authenticated state
        this.isAuthenticated = true;

        // Initialize player
        await playerService.initialize();
      } catch (error) {
        console.error('Error initializing Spotify:', error);
        this.setError('Failed to initialize Spotify');
      }
    },

    /**
     * Set the player instance
     * @param player Player instance
     */
    setPlayer(player: any) {
      this.player = player;
    },

    /**
     * Set the device ID
     * @param deviceId Device ID
     */
    setDeviceId(deviceId: string | null) {
      this.deviceId = deviceId;
    },

    /**
     * Set the current track
     * @param track Track
     */
    setCurrentTrack(track: WebPlaybackTrack | null) {
      this.currentTrack = track;
    },

    /**
     * Set the playing state
     * @param isPlaying Playing state
     */
    setPlayingState(isPlaying: boolean) {
      this.isPlaying = isPlaying;
    },

    /**
     * Set the volume
     * @param volume Volume
     */
    setVolume(volume: number) {
      this.volume = volume;
      localStorage.setItem('spotify_volume', volume.toString());
    },

    /**
     * Set error message
     * @param error Error message
     */
    setError(error: string | null) {
      this.error = error;
    },

    /**
     * Toggle expanded state
     */
    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
      localStorage.setItem('spotify_expanded', this.isExpanded.toString());
    },

    /**
     * Toggle minimized state
     */
    toggleMinimized() {
      this.isMinimized = !this.isMinimized;
      localStorage.setItem('spotify_minimized', this.isMinimized.toString());
    },

    /**
     * Toggle play/pause
     */
    async togglePlay() {
      if (this.playerService) {
        await this.playerService.togglePlay();
      }
    },

    /**
     * Skip to previous track
     */
    async previousTrack() {
      if (this.playerService) {
        await this.playerService.previousTrack();
      }
    },

    /**
     * Skip to next track
     */
    async nextTrack() {
      if (this.playerService) {
        await this.playerService.nextTrack();
      }
    },

    /**
     * Seek to position
     * @param positionMs Position in milliseconds
     */
    async seekTo(positionMs: number) {
      if (this.playerService) {
        await this.playerService.seekTo(positionMs);
      }
    },

    /**
     * Update playback position
     * @param position Position in milliseconds
     */
    async updatePlaybackPosition(position: number) {
      if (this.playerService) {
        await this.playerService.seekTo(position);
      }
    },

    /**
     * Toggle shuffle mode
     */
    async toggleShuffle() {
      if (this.playerService) {
        await this.playerService.toggleShuffle();
      }
    },

    /**
     * Clean up resources
     */
    cleanup() {
      if (this.playerService) {
        this.playerService.disconnect();
      }

      this.player = null;
      this.playerService = null;
      this.deviceId = null;
      this.isInitialized = false;
      this.isAuthenticated = false;
      this.currentTrack = null;
      this.isPlaying = false;
      this.error = null;
    }
  }
});