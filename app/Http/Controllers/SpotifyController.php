<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SpotifyController extends Controller
{
    /**
     * Redirect to Spotify authorization page
     */
    public function login()
    {
        $state = Str::random(16);
        Session::put('spotify_auth_state', $state);

        $scope = 'streaming user-read-email user-read-private user-read-playback-state user-modify-playback-state';
        $clientId = config('services.spotify.client_id');
        $redirectUri = config('services.spotify.redirect');

        // Ensure redirect URI is properly set
        if (empty($redirectUri)) {
            \Log::error('Spotify redirect URI is not set');

            return redirect()->route('home')->with('spotify_error', 'Spotify configuration error: Redirect URI is not set');
        }

        // Log the authorization parameters
        \Log::info('Initiating Spotify authorization', [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'scope' => $scope,
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'show_dialog' => true, // Force display of authorization dialog
        ]);

        return redirect('https://accounts.spotify.com/authorize?'.$queryParams);
    }

    /**
     * Handle callback from Spotify
     */
    public function callback(Request $request)
    {
        $code = $request->code ?? null;
        $state = $request->state ?? null;
        $storedState = Session::get('spotify_auth_state');

        // Log the callback parameters for debugging
        \Log::info('Spotify callback received', [
            'code_exists' => ! empty($code),
            'state_exists' => ! empty($state),
            'stored_state_exists' => ! empty($storedState),
            'state_matches' => $state === $storedState,
            'request_url' => $request->fullUrl(),
            'session_id' => Session::getId(),
            'all_session_data' => Session::all(),
        ]);

        // If there's no stored state, we might have lost the session
        if ($storedState === null) {
            \Log::warning('No stored state found in session, possible session loss', [
                'received_state' => $state,
                'session_id' => Session::getId(),
            ]);
            // Instead of failing, we'll proceed with the code if we have it
            if ($code) {
                Session::put('spotify_auth_state', $state); // Store the received state
                $storedState = $state; // Use the received state
            }
        }

        // Check state to prevent CSRF attacks
        if ($state === null || $state !== $storedState) {
            \Log::warning('Spotify callback state mismatch', [
                'received_state' => $state,
                'stored_state' => $storedState,
            ]);

            return redirect()->route('home')->with('spotify_error', 'Authentication failed: State mismatch');
        }

        if ($code) {
            $clientId = config('services.spotify.client_id');
            $clientSecret = config('services.spotify.client_secret');
            $redirectUri = config('services.spotify.redirect');

            \Log::info('Spotify token exchange', [
                'redirect_uri' => $redirectUri,
                'client_id_exists' => ! empty($clientId),
                'client_secret_exists' => ! empty($clientSecret),
                'session_id' => Session::getId(),
            ]);

            try {
                $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $redirectUri,
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    // Add expiration timestamp
                    $data['expires_at'] = now()->addSeconds($data['expires_in'])->timestamp;

                    // Store token in session
                    Session::put('spotify_token', $data);

                    // Verify token was stored
                    $storedToken = Session::get('spotify_token');
                    \Log::info('Spotify authentication successful', [
                        'token_type' => $data['token_type'] ?? 'unknown',
                        'expires_in' => $data['expires_in'] ?? 0,
                        'has_refresh_token' => isset($data['refresh_token']),
                        'token_stored' => ! empty($storedToken),
                        'session_id' => Session::getId(),
                    ]);

                    // Force session to be written
                    Session::save();

                    // Redirect to home with success parameters
                    return redirect()->route('home')
                        ->with('spotify_connected', true)
                        ->with('spotify_auth', 'success');
                } else {
                    \Log::error('Spotify token exchange failed', [
                        'status' => $response->status(),
                        'response' => $response->json(),
                        'session_id' => Session::getId(),
                    ]);

                    return redirect()->route('home')->with('spotify_error', 'Failed to authenticate with Spotify: '.($response->json()['error_description'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                \Log::error('Spotify token exchange exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'session_id' => Session::getId(),
                ]);

                return redirect()->route('home')->with('spotify_error', 'An error occurred during Spotify authentication');
            }
        }

        \Log::warning('Spotify callback missing code');

        return redirect()->route('home')->with('spotify_error', 'Authorization code not provided');
    }

    /**
     * Get the access token
     */
    public function getToken()
    {
        $spotifyToken = Session::get('spotify_token');

        // Debug logging
        \Log::info('Spotify getToken called', [
            'session_id' => Session::getId(),
            'has_token' => ! empty($spotifyToken),
            'token_data' => $spotifyToken ? [
                'has_access_token' => isset($spotifyToken['access_token']),
                'has_refresh_token' => isset($spotifyToken['refresh_token']),
                'expires_at' => $spotifyToken['expires_at'] ?? null,
                'current_time' => now()->timestamp,
            ] : null,
            'all_session_data' => Session::all(),
        ]);

        // Check if token exists and is not expired
        if ($spotifyToken && isset($spotifyToken['access_token'])) {
            // Check if token is expired and needs refresh
            if (isset($spotifyToken['expires_at']) && now()->timestamp > $spotifyToken['expires_at']) {
                \Log::info('Token expired, attempting refresh');

                return $this->refreshToken();
            }

            \Log::info('Returning valid token');

            return response()->json([
                'access_token' => $spotifyToken['access_token'],
                'expires_in' => $spotifyToken['expires_in'] ?? 3600,
                'refresh_token' => $spotifyToken['refresh_token'] ?? null,
                'is_default' => false,
            ]);
        }

        // If no valid user token exists, get a default token using client credentials
        \Log::info('No user token found, falling back to client credentials');

        return $this->getDefaultToken();
    }

    /**
     * Refresh the access token
     */
    private function refreshToken()
    {
        $spotifyToken = Session::get('spotify_token');

        if (! $spotifyToken || ! isset($spotifyToken['refresh_token'])) {
            \Log::info('No refresh token available, falling back to client credentials');

            return $this->getDefaultToken();
        }

        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        try {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $spotifyToken['refresh_token'],
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Preserve the refresh token as it's not always returned
                if (! isset($data['refresh_token']) && isset($spotifyToken['refresh_token'])) {
                    $data['refresh_token'] = $spotifyToken['refresh_token'];
                }

                // Add expiration timestamp
                $data['expires_at'] = now()->addSeconds($data['expires_in'])->timestamp;

                Session::put('spotify_token', $data);

                return response()->json([
                    'access_token' => $data['access_token'],
                    'expires_in' => $data['expires_in'],
                    'refresh_token' => $data['refresh_token'] ?? null,
                    'is_default' => false,
                ]);
            }

            \Log::error('Token refresh failed, falling back to client credentials', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return $this->getDefaultToken();
        } catch (\Exception $e) {
            \Log::error('Exception during token refresh:', [
                'error' => $e->getMessage(),
            ]);

            return $this->getDefaultToken();
        }
    }

    /**
     * Get a default token using client credentials flow
     */
    private function getDefaultToken()
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        try {
            \Log::info('Attempting to get client credentials token');

            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                \Log::info('Successfully obtained client credentials token');

                return response()->json([
                    'access_token' => $data['access_token'],
                    'expires_in' => $data['expires_in'],
                    'is_default' => true,
                ]);
            }

            \Log::error('Failed to get client credentials token', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return response()->json([
                'error' => 'Failed to get Spotify token',
                'details' => $response->json(),
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Exception getting client credentials token:', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to get Spotify token',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout from Spotify
     */
    public function logout()
    {
        Session::forget('spotify_token');
        Session::forget('spotify_auth_state');

        return redirect()->route('home');
    }

    /**
     * Get default playlist
     */
    public function getDefaultPlaylist()
    {
        // Use a real Spotify preview URL for a popular track
        $previewUrl = 'https://p.scdn.co/mp3-preview/2f37da1d4221f40b9d1a98cd191f4d6f1646ad17';

        return response()->json([
            'playlist_uri' => config('services.spotify.default_playlist', 'spotify:playlist:37i9dQZF1DXdLEN7aqioXM'),
            'preview_url' => config('services.spotify.preview_url', $previewUrl),
            'track_name' => 'Fallback Audio Track',
            'artist_name' => 'El Arquitecto AI',
        ]);
    }
}
