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
        ]);

        return redirect('https://accounts.spotify.com/authorize?' . $queryParams);
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
            'code_exists' => !empty($code),
            'state_exists' => !empty($state),
            'stored_state_exists' => !empty($storedState),
            'state_matches' => $state === $storedState,
            'request_url' => $request->fullUrl(),
            'session_id' => Session::getId(),
            'all_session_data' => Session::all()
        ]);

        // If there's no stored state, we might have lost the session
        if ($storedState === null) {
            \Log::warning('No stored state found in session, possible session loss', [
                'received_state' => $state
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
                'client_id_exists' => !empty($clientId),
                'client_secret_exists' => !empty($clientSecret),
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

                    Session::put('spotify_token', $data);
                    \Log::info('Spotify authentication successful', [
                        'token_type' => $data['token_type'] ?? 'unknown',
                        'expires_in' => $data['expires_in'] ?? 0,
                    ]);

                    return redirect()->route('home')->with('spotify_connected', true);
                } else {
                    \Log::error('Spotify token exchange failed', [
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                    return redirect()->route('home')->with('spotify_error', 'Failed to authenticate with Spotify: ' . ($response->json()['error_description'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                \Log::error('Spotify token exchange exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
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

        // Check if token exists and is not expired
        if ($spotifyToken && isset($spotifyToken['access_token'])) {
            // Check if token is expired and needs refresh
            if (isset($spotifyToken['expires_at']) && now()->timestamp > $spotifyToken['expires_at']) {
                return $this->refreshToken();
            }

            return response()->json([
                'access_token' => $spotifyToken['access_token'],
                'expires_in' => $spotifyToken['expires_in'] ?? 3600,
            ]);
        }

        // If user is not authenticated, use default credentials
        return $this->getDefaultToken();
    }

    /**
     * Refresh the access token
     */
    private function refreshToken()
    {
        $spotifyToken = Session::get('spotify_token');

        if (!$spotifyToken || !isset($spotifyToken['refresh_token'])) {
            return $this->getDefaultToken();
        }

        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $spotifyToken['refresh_token'],
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Preserve the refresh token as it's not always returned
            if (!isset($data['refresh_token']) && isset($spotifyToken['refresh_token'])) {
                $data['refresh_token'] = $spotifyToken['refresh_token'];
            }

            // Add expiration timestamp
            $data['expires_at'] = now()->addSeconds($data['expires_in'])->timestamp;

            Session::put('spotify_token', $data);

            return response()->json([
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
            ]);
        }

        // If refresh fails, fall back to default token
        return $this->getDefaultToken();
    }

    /**
     * Get a default token using client credentials flow
     */
    private function getDefaultToken()
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            return response()->json([
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
                'is_default' => true,
            ]);
        }

        return response()->json(['error' => 'Failed to get Spotify token'], 500);
    }

    /**
     * Logout from Spotify
     */
    public function logout()
    {
        Session::forget('spotify_token');
        Session::forget('spotify_auth_state');

        return redirect()->route('welcome');
    }

    /**
     * Get default playlist
     */
    public function getDefaultPlaylist()
    {
        return response()->json([
            'playlist_uri' => config('services.spotify.default_playlist'),
        ]);
    }
}