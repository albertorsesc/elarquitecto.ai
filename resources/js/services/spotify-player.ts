import { useSpotifyStore } from '@/stores/useSpotifyStore';
import { SpotifyApi } from '@spotify/web-api-ts-sdk';

// Extend the Window interface to include Spotify Web Playback SDK
declare global {
  interface Window {
    onSpotifyWebPlaybackSDKReady: () => void;
    Spotify: {
      Player: new (config: any) => any;
    };
  }
}

/**
 * Spotify Player Service
 * Handles initialization and interaction with the Spotify Web Playback SDK
 */
export class SpotifyPlayerService {
  private player: any;
  private store = useSpotifyStore();

  /**
   * Constructor
   * @param sdk The Spotify API SDK instance
   */
  constructor(private sdk: SpotifyApi) {}

  /**
   * Initialize the Spotify Web Playback SDK
   */
  async initialize(): Promise<void> {
    try {
      // Set up the SDK ready callback before loading the script
      window.onSpotifyWebPlaybackSDKReady = () => {
        this.initializePlayer();
      };

      // Load the Spotify Web Playback SDK script
      await this.loadScript('https://sdk.scdn.co/spotify-player.js');
    } catch (error) {
      console.error('Error initializing Spotify player:', error);
      this.store.setError('Failed to initialize Spotify player');
    }
  }

  /**
   * Load a script dynamically
   * @param src Script source URL
   */
  private loadScript(src: string): Promise<void> {
    return new Promise((resolve, reject) => {
      if (document.getElementById('spotify-player-sdk')) {
        resolve();
        return;
      }

      const script = document.createElement('script');
      script.id = 'spotify-player-sdk';
      script.src = src;
      script.async = true;

      script.onload = () => resolve();
      script.onerror = (error) => {
        console.error('Error loading Spotify SDK script:', error);
        reject(new Error('Failed to load Spotify SDK script'));
      };

      document.head.appendChild(script);
    });
  }

  /**
   * Initialize the Spotify Web Player
   */
  private async initializePlayer(): Promise<void> {
    try {
      console.log('Initializing Spotify Web Player...');

      this.player = new window.Spotify.Player({
        name: 'El Arquitecto A.I. Web Player',
        getOAuthToken: async (callback: (token: string) => void) => {
          try {
            console.log('Getting OAuth token for player...');
            const token = await this.sdk.getAccessToken();

            if (!token) {
              console.error('No token received from SDK');
              this.store.setError('Authentication failed - no token received');
              window.location.href = '/spotify/login';
              return;
            }

            if (!token.access_token) {
              console.error('Token received but no access_token present:', token);
              this.store.setError('Authentication failed - invalid token');
              window.location.href = '/spotify/login';
              return;
            }

            console.log('Successfully retrieved token for player');
            callback(token.access_token);
          } catch (error) {
            console.error('Error getting OAuth token:', error);
            this.store.setError('Failed to get authentication token');
            window.location.href = '/spotify/login';
          }
        },
        volume: this.store.volume
      });

      console.log('Registering event handlers...');
      this.registerEventHandlers();

      console.log('Connecting to Spotify...');
      await this.connect();

      console.log('Player initialization complete');
    } catch (error) {
      console.error('Error initializing player:', error);
      this.store.setError('Failed to initialize player');
    }
  }

  /**
   * Register event handlers for the Spotify Web Player
   */
  private registerEventHandlers(): void {
    // Error handlers
    this.player.addListener('initialization_error', ({ message }: { message: string }) => {
      console.error('Spotify Player initialization error:', message);
      this.store.setError(`Initialization error: ${message}`);
    });

    this.player.addListener('authentication_error', ({ message }: { message: string }) => {
      console.error('Spotify Player authentication error:', message);
      this.store.setError(`Authentication error: ${message}`);
      // Redirect to login
      window.location.href = '/spotify/login';
    });

    this.player.addListener('account_error', ({ message }: { message: string }) => {
      console.error('Spotify Player account error:', message);
      this.store.setError(`Account error: ${message}`);
    });

    this.player.addListener('playback_error', ({ message }: { message: string }) => {
      console.error('Spotify Player playback error:', message);
      this.store.setError(`Playback error: ${message}`);
    });

    // Ready handler
    this.player.addListener('ready', ({ device_id }: { device_id: string }) => {
      console.log('Spotify Player ready with device ID:', device_id);
      this.store.setDeviceId(device_id);
      this.store.setError(null);

      // Transfer playback to this device
      this.transferPlayback(device_id);
    });

    // Not ready handler
    this.player.addListener('not_ready', ({ device_id }: { device_id: string }) => {
      console.log('Spotify Player device ID has gone offline:', device_id);
      if (this.store.deviceId === device_id) {
        this.store.setDeviceId(null);
      }
    });

    // Player state changed handler
    this.player.addListener('player_state_changed', (state: any) => {
      if (!state) {
        console.log('Spotify Player state update: null state received');
        return;
      }

      console.log('Spotify Player state updated:', {
        track: state.track_window?.current_track?.name,
        artist: state.track_window?.current_track?.artists?.[0]?.name,
        playing: !state.paused,
        position: state.position,
        duration: state.duration,
        shuffle: state.shuffle
      });

      // Update track information
      this.store.setCurrentTrack(state.track_window?.current_track);
      this.store.trackDuration = state.duration;

      // Update playback state
      this.store.setPlayingState(!state.paused);
      this.store.playbackPosition = state.position;

      // Update shuffle state
      this.store.shuffleEnabled = state.shuffle;
      localStorage.setItem('spotify_shuffle', String(state.shuffle));
    });
  }

  /**
   * Connect to the Spotify Web Player
   */
  private async connect(): Promise<void> {
    try {
      const connected = await this.player.connect();

      if (connected) {
        console.log('Spotify Player connected successfully');
        this.store.setPlayer(this.player);
        this.store.isInitialized = true;
        this.startStatePolling();
      } else {
        throw new Error('Failed to connect to Spotify Player');
      }
    } catch (error) {
      console.error('Error connecting to Spotify Player:', error);
      this.store.setError('Failed to connect to Spotify Player');
    }
  }

  /**
   * Transfer playback to the current device
   * @param deviceId The device ID to transfer playback to
   */
  private async transferPlayback(deviceId: string): Promise<void> {
    try {
      await this.sdk.player.transferPlayback([deviceId], true);
      console.log('Playback transferred to current device');
    } catch (error) {
      console.error('Error transferring playback:', error);
    }
  }

  /**
   * Start polling for player state
   */
  private startStatePolling(): void {
    // Poll every second for smooth updates
    setInterval(async () => {
      if (!this.player || !this.store.isInitialized) return;

      try {
        const state = await this.player.getCurrentState();
        if (state) {
          // Update position for smoother progress bar
          this.store.playbackPosition = state.position;
        }
      } catch (error) {
        console.error('Error polling player state:', error);
      }
    }, 1000);
  }

  /**
   * Toggle play/pause
   */
  async togglePlay(): Promise<void> {
    if (!this.player) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      await this.player.togglePlay();
      console.log('Toggle play command sent');
    } catch (error) {
      console.error('Error toggling play state:', error);
      this.store.setError('Failed to toggle playback');
    }
  }

  /**
   * Skip to previous track
   */
  async previousTrack(): Promise<void> {
    if (!this.player) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      await this.player.previousTrack();
      console.log('Previous track command sent');
    } catch (error) {
      console.error('Error skipping to previous track:', error);
      this.store.setError('Failed to skip to previous track');
    }
  }

  /**
   * Skip to next track
   */
  async nextTrack(): Promise<void> {
    if (!this.player) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      await this.player.nextTrack();
      console.log('Next track command sent');
    } catch (error) {
      console.error('Error skipping to next track:', error);
      this.store.setError('Failed to skip to next track');
    }
  }

  /**
   * Seek to position
   * @param positionMs Position in milliseconds
   */
  async seekTo(positionMs: number): Promise<void> {
    if (!this.player) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      await this.player.seek(positionMs);
      console.log(`Seek to position ${positionMs}ms command sent`);

      // Update local state immediately for better UX
      this.store.playbackPosition = positionMs;
      sessionStorage.setItem('spotify_playback_position', String(positionMs));
    } catch (error) {
      console.error('Error seeking to position:', error);
      this.store.setError('Failed to seek to position');
    }
  }

  /**
   * Set volume
   * @param volume Volume level (0-1)
   */
  async setVolume(volume: number): Promise<void> {
    if (!this.player) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      await this.player.setVolume(volume);
      console.log(`Set volume to ${volume} command sent`);

      // Update local state
      this.store.setVolume(volume);
      localStorage.setItem('spotify_volume', String(volume));
    } catch (error) {
      console.error('Error setting volume:', error);
      this.store.setError('Failed to set volume');
    }
  }

  /**
   * Toggle shuffle mode
   */
  async toggleShuffle(): Promise<void> {
    if (!this.player || !this.store.deviceId) {
      this.store.setError('Player not initialized');
      return;
    }

    try {
      const newShuffleState = !this.store.shuffleEnabled;

      // Update shuffle state on the Spotify API
      await this.sdk.player.togglePlaybackShuffle(newShuffleState);
      console.log(`Shuffle ${newShuffleState ? 'enabled' : 'disabled'}`);

      // Update local state
      this.store.shuffleEnabled = newShuffleState;
      localStorage.setItem('spotify_shuffle', String(newShuffleState));
    } catch (error) {
      console.error('Error toggling shuffle:', error);
      this.store.setError('Failed to toggle shuffle mode');
    }
  }

  /**
   * Disconnect the player
   */
  disconnect(): void {
    if (this.player) {
      this.player.disconnect();
      this.store.setPlayer(null);
      this.store.setDeviceId(null);
      this.store.isInitialized = false;
    }
  }
}

export default SpotifyPlayerService;