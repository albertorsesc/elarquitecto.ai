Directory structure:
└── albertorsesc-spotify-2.0/
    ├── README.md
    ├── middleware.ts
    ├── next.config.js
    ├── package.json
    ├── postcss.config.js
    ├── tailwind.config.js
    ├── tsconfig.json
    ├── .env.example
    ├── atoms/
    │   ├── playlist.atom.js
    │   └── song.atom.js
    ├── components/
    │   ├── Center.js
    │   ├── Player.js
    │   ├── Sidebar.js
    │   ├── Song.js
    │   └── Songs.js
    ├── hooks/
    │   ├── useSongInfo.hook.js
    │   └── useSpotify.hook.js
    ├── lib/
    │   ├── spotify.js
    │   └── time.js
    ├── pages/
    │   ├── _app.tsx
    │   ├── index.tsx
    │   ├── login.js
    │   └── api/
    │       ├── hello.ts
    │       └── auth/
    │           └── [...nextauth].js
    ├── public/
    └── styles/
        └── globals.css


Files Content:

================================================
File: README.md
================================================
### Spotify 2.0 (side project)

![](https://github.com/albertorsesc/spotify-2.0/blob/master/public/spotify-demo.png)

## Installation

### Requirements

Spotify API keys.

* Visit: `https://developer.spotify.com/dashboard/applications`
* Login
* Create an App
* Retrieve: `Client ID` and `Client Secret` and assign to `.env` file

### Install

Clone Repo

```shell
git clone https://github.com/albertorsesc/spotify-2.0.git;
cd spotify-2.0;
cp .env.example .env;
```

Install NPM dependencies

```shell
npm install;
```

Run Project

```shell
npm run dev;
```


#### Features

* Retrieve Playlists
* Retrieve List of Songs per Playlist
* Player:
  * Play Song
  * Pause Song
  * Adjust Volume

### Spotify Quirks

In order to use the Spotify API you must have an ACTIVE_DEVICE.

* Open Web/Desktop/Mobile player
* Hit play then pause to "wake" the Active state.

>Note: Previous Song and Skip Song are implemented although not usable due to some issues with the Spotify API (IMO)




================================================
File: middleware.ts
================================================
import { getToken } from "next-auth/jwt";
import { NextRequest, NextResponse } from "next/server";

export const middleware = async (request: NextRequest) => {
  const url = request.nextUrl;

  const token = await getToken({
    req: request,
    secret: process.env.JWT_SECRET
  });

  // ToDo: Redirect Home if already logged in.

  if (url.pathname.includes('/api/auth') || token) {
    return NextResponse.next();
  }

  if (!token && url.pathname !== '/login') {
    const clonedUrl = url.clone();
    clonedUrl.pathname = '/login';
    return NextResponse.redirect(clonedUrl);
  }
}

export const config = {
  matcher: ['/']
}


================================================
File: next.config.js
================================================
/** @type {import('next').NextConfig} */
module.exports = {
  reactStrictMode: true,
}



================================================
File: package.json
================================================
{
  "private": true,
  "scripts": {
    "dev": "next dev",
    "build": "next build",
    "start": "next start"
  },
  "dependencies": {
    "@heroicons/react": "^2.0.11",
    "lodash": "^4.17.21",
    "next": "latest",
    "next-auth": "^4.12.2",
    "react": "18.1.0",
    "react-dom": "18.1.0",
    "recoil": "^0.7.5",
    "spotify-web-api-node": "^5.0.2",
    "tailwind-scrollbar-hide": "^1.1.7"
  },
  "devDependencies": {
    "@types/node": "17.0.35",
    "@types/react": "18.0.9",
    "@types/react-dom": "18.0.5",
    "autoprefixer": "^10.4.7",
    "postcss": "^8.4.14",
    "tailwindcss": "^3.1.2",
    "typescript": "4.7.2"
  }
}



================================================
File: postcss.config.js
================================================
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}



================================================
File: tailwind.config.js
================================================
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx}',
    './components/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwind-scrollbar-hide'),
  ],
}



================================================
File: tsconfig.json
================================================
{
  "compilerOptions": {
    "target": "es5",
    "lib": ["dom", "dom.iterable", "esnext"],
    "allowJs": true,
    "skipLibCheck": true,
    "strict": true,
    "forceConsistentCasingInFileNames": true,
    "noEmit": true,
    "esModuleInterop": true,
    "module": "esnext",
    "moduleResolution": "node",
    "resolveJsonModule": true,
    "isolatedModules": true,
    "jsx": "preserve",
    "incremental": true
  },
  "include": ["next-env.d.ts", "**/*.ts", "**/*.tsx"],
  "exclude": ["node_modules"]
}



================================================
File: .env.example
================================================
NEXTAUTH_URL=http://localhost:3000

#Spotify API - https://developer.spotify.com/dashboard/applications/
NEXT_PUBLIC_CLIENT_ID=<SPOTIFY_CLIENT_ID>
NEXT_PUBLIC_CLIENT_SECRET=<SPOTIFY_CLIENT_SECRET>

JWT_SECRET=super_secret_token



================================================
File: atoms/playlist.atom.js
================================================
import { atom } from 'recoil';

export const playlistStateAtom = atom({
  key: 'playlistState',
  default: null,
});

export const playlistIdStateAtom = atom({
  key: 'playlistIdState',
  default: '1dMwIj7IIfKnMJBa03izm1',
});


================================================
File: atoms/song.atom.js
================================================
import { atom } from 'recoil';

export const currentTrackIdStateAtom = atom({
  key: 'currentTrackIdState',
  default: null,
});

export const isPlayingStateAtom = atom({
  key: 'isPlayingState',
  default: false,
});


================================================
File: components/Center.js
================================================
import { useSession } from "next-auth/react";
import { useEffect, useState } from "react";

import { signOut } from "next-auth/react";

import { shuffle } from 'lodash';

import { useRecoilState, useRecoilValue } from 'recoil';
import { playlistIdStateAtom, playlistStateAtom } from '../atoms/playlist.atom';

import { ChevronDownIcon } from "@heroicons/react/24/outline";
import useSpotify from "../hooks/useSpotify.hook";

import Songs from '../components/Songs';

const gradientColors = [
  'from-indigo-500',
  'from-blue-500',
  'from-green-500',
  'from-red-500',
  'from-yellow-500',
  'from-pink-500',
  'from-purple-500',
];

const Center = () => {
  const spotifyApi = useSpotify();
  const { data: session } = useSession();
  const [color, setColor] = useState(null);
  const playlistId = useRecoilValue(playlistIdStateAtom);
  const [playlist, setPlaylist] = useRecoilState(playlistStateAtom);

  useEffect(() => {
    setColor(shuffle(gradientColors).pop());
  }, [playlistId]);

  useEffect(() => {
    spotifyApi.getPlaylist(playlistId).then(data => {
        setPlaylist(data.body);
    }).catch(error => console.log('spotifyApi.getPlaylist', error));
  }, [spotifyApi, playlistId]);

  return (
    <div className="flex-grow h-screen overflow-y-scroll scrollbar-hide">

      <header className="absolute top-5 right-8">

        <div onClick={signOut} className="flex items-center bg-black space-x-3 opacity-90 hover:opacity-80 cursor-pointer rounded-full p-1 pr-2">
          <img src={session?.user.image ?? 'https://avatars.githubusercontent.com/u/22654040?s=400&u=b4c45a0a60117314537fa0ef7aec04e2038e07da&v=4'}
            className='rounded-full w-10 h-10 object-cover'
            alt="" />
        <h2 className="text-white">{session?.user.name}</h2>
        <ChevronDownIcon className="h-5 w-5" />
        </div>
      </header>

      <section className={`flex items-end space-x-7 bg-gradient-to-b to-black ${color} h-80 text-white p-8`}>
        <img src={playlist?.images?.[0]?.url} className='h-44 w-44 shadow-2xl' />

        <div>
          <p>PLAYLIST</p>
          <h1 className="text-2xl md:text-3xl xl:text-5xl font-bold">{playlist?.name}</h1>
        </div>
      </section>

      <div>
        <Songs />
      </div>

    </div>
  );
}

export default Center;


================================================
File: components/Player.js
================================================
import {
  ArrowsRightLeftIcon,
  ArrowUturnLeftIcon,
  SpeakerWaveIcon as SpeakerWaveOutlineIcon
} from '@heroicons/react/24/outline';
import { BackwardIcon, ForwardIcon, PauseCircleIcon, PlayCircleIcon, SpeakerWaveIcon } from '@heroicons/react/24/solid';

import { debounce } from 'lodash';


import { useSession } from "next-auth/react";
import { useCallback, useEffect, useState } from "react";
import { useRecoilState } from 'recoil';
import { currentTrackIdStateAtom, isPlayingStateAtom } from "../atoms/song.atom";
import useSongInfo from "../hooks/useSongInfo.hook";
import useSpotify from "../hooks/useSpotify.hook";

const Player = () => {
  const spotifyApi = useSpotify();
  const { data: session, status } = useSession();

  const [currentTrackId, setCurrentTrackId] = useRecoilState(currentTrackIdStateAtom);
  const [isPlaying, setIsPlaying] = useRecoilState(isPlayingStateAtom);
  const [volume, setVolume] = useState(50);

  const songInfo = useSongInfo();

  const fetchCurrentSong = () => {
    if (!songInfo) {
      spotifyApi.getMyCurrentPlayingTrack().then(data => {
        console.log('Now playing: ', data.body?.item);
        setCurrentTrackId(data.body?.item?.id);

        spotifyApi.getMyCurrentPlaybackState().then(data => {
          setIsPlaying(data.body?.is_playing);
        })
      })
    }
  };

  const handleTogglePlayer = () => {
    spotifyApi.getMyCurrentPlaybackState().then(data => {
      if (data.body.is_playing) {
        spotifyApi.pause();
        setIsPlaying(false);
      } else {
        spotifyApi.play();
        setIsPlaying(true);
      }
    })
  };

  useEffect(() => {
    if (spotifyApi.getAccessToken() && !currentTrackId) {
      fetchCurrentSong()
      setVolume(50);
    }
  }, [currentTrackId, spotifyApi, session]);

  useEffect(() => {
    if (volume > 0 && volume < 100) {
      debouncedAdjustVolume(volume);
    }
  }, [volume]);

  const debouncedAdjustVolume = useCallback(
    debounce(() => {
      spotifyApi.setVolume(volume).catch(error => {});
    }, 500, [])
  );

  return (
    <div className="h-24 bg-gradient-to-b from-black to-gray-900 text-white grid grid-cols-3 text-xs md:text-base px-2 md:px-8">
      {/* Left */}
      <div className="flex items-center space-x-4">
        <img src={songInfo?.album?.images?.[0]?.url}
          className='hidden md:inline h-10 w-10' />

          <div>
            <h3>{songInfo?.name}</h3>
            <p>{songInfo?.artists?.[0]?.name}</p>
          </div>
      </div>

      {/* Center */}
      <div className='flex items-center justify-evenly'>
        <ArrowsRightLeftIcon className='button' />
        <BackwardIcon onClick={() => spotifyApi.skipToPrevious()} className='button' />

        {
          isPlaying ? (
            <PauseCircleIcon onClick={handleTogglePlayer} className='button w-10 h-10' />
          ) : (
            <PlayCircleIcon onClick={handleTogglePlayer} className='button w-10 h-10' />
          )
        }

        <ForwardIcon onClick={() => spotifyApi.skipToNext()} className='button' />
        <ArrowUturnLeftIcon className='button' />
      </div>

      {/* Right */}
      <div className='flex items-center space-x-3 md:space-x-4 justify-end pr-5'>
        <SpeakerWaveOutlineIcon onClick={() => volume > 0 && setVolume(volume - 10)} className='button' />
        <input type='range' onChange={e => setVolume(Number(e.target.value))} className='w-14 md:w-28' value={volume} min={0} max={100} />
        <SpeakerWaveIcon onClick={() => volume < 100 && setVolume(volume + 10)} className='button' />
      </div>
    </div>
  )
};

export default Player;


================================================
File: components/Sidebar.js
================================================
import { useSession } from 'next-auth/react';
import { useEffect, useState } from 'react';

import useSpotify from '../hooks/useSpotify.hook';

import { useRecoilState } from 'recoil';
import { playlistIdStateAtom } from '../atoms/playlist.atom';

import {
  BuildingLibraryIcon,
  HeartIcon,
  HomeIcon,
  MagnifyingGlassIcon,
  PlusCircleIcon,
  RssIcon
} from '@heroicons/react/24/outline';

function Sidebar() {
  const spotifyApi = useSpotify();
  const { data: session, status } = useSession();
  const [playlists, setPlaylists] = useState([]);
  const [playlistId, setPlaylistId] = useRecoilState(playlistIdStateAtom);

  useEffect(() => {
    if (spotifyApi.getAccessToken()) {
      spotifyApi.getUserPlaylists()
        .then(data => {
          setPlaylists(data.body.items);
        })
    }
  }, [session, spotifyApi]);

  return (
    <div className='text-gray-500 p-5 text-xs lg:text-sm border-r border-gray-900 overflow-y-scroll scrollbar-hide h-screen sm:max-w-[12rem] lg:max-w-[15rem] hidden md:inline-flex pb-36'>

      <div className='space-y-4'>
        <button className='flex items-center space-x-2 hover:text-white'>
          <HomeIcon className='h-5 w-5' />
          <p>Home</p>
        </button>
        <button className='flex items-center space-x-2 hover:text-white'>
          <MagnifyingGlassIcon className='h-5 w-5' />
          <p>Search</p>
        </button>
        <button className='flex items-center space-x-2 hover:text-white'>
          <BuildingLibraryIcon className="w-5 h-5" />
          <p>Your Library</p>
        </button>

        <hr className='border-t-[0.1px] border-gray-900' />

        <button className='flex items-center space-x-2 hover:text-white'>
          <PlusCircleIcon className="w-5 h-5" />
          <p>Create Playlist</p>
        </button>

        <button className='flex items-center space-x-2 hover:text-white'>
          <HeartIcon className='w-5 h-5' />
          <p>Liked Songs</p>
        </button>
        <button className='flex items-center space-x-2 hover:text-white'>
          <RssIcon className="w-5 h-5" />
          <p>Your episodes</p>
        </button>

        <hr className='border-t-[0.1px] border-gray-900' />

        {/* Playlist */}
        {
          playlists.map(playlist => (
            <p key={playlist.id}
              onClick={() => setPlaylistId(playlist.id)}
              className='cursor-pointer hover:text-white'>
              {playlist.name}
            </p>
          ))
        }
      </div>

    </div>
  );
}

export default Sidebar;


================================================
File: components/Song.js
================================================
import { useRecoilState } from 'recoil';
import { currentTrackIdStateAtom, isPlayingStateAtom } from '../atoms/song.atom';
import useSpotify from "../hooks/useSpotify.hook";

import millisecondsToMinutesAndSeconds from '../lib/time';

const Song = ({ order, track }) => {
  const song = track.track;
  const spotifyApi = useSpotify();
  const [currentTrackId, setCurrentTrackId] = useRecoilState(currentTrackIdStateAtom);
  const [isPlaying, setIsPlaying] = useRecoilState(isPlayingStateAtom);

  const playSong = () => {
    setCurrentTrackId(song.id);
    setIsPlaying(true);
    spotifyApi.play({
      uris: [song.uri],
    });
  };

  return (
    <div onClick={playSong} className="grid grid-cols-2 text-gray-500 py-4 px-5 hover:bg-gray-900 rounded-lg cursor-pointer">
      <div className="flex items-center space-x-4">
        <p>{order + 1}</p>
        <img src={song.album.images[0].url} className='h-10 w-10 rounded-lg' />
        <div>
          <p className="w-36 lg:w-64 truncate text-white">{song.name}</p>
          <p className="w-40">{song.artists[0].name}</p>
        </div>
      </div>

      <div className="flex items-center justify-between ml-auto md:ml-0">
        <p className="w-40 hidden md:inline-flex ">{song.album.name}</p>
        <p>{millisecondsToMinutesAndSeconds(song.duration_ms)}</p>
      </div>
    </div>
  );
};

export default Song;


================================================
File: components/Songs.js
================================================
import { useRecoilValue } from 'recoil';
import { playlistStateAtom } from "../atoms/playlist.atom";

import Song from '../components/Song';

const Songs = () => {
  const playlist = useRecoilValue(playlistStateAtom)
  return (
    <div className='text-white px-8 flex flex-col space-y-1 pb-28'>
      {
        playlist?.tracks.items.map((track, index) => (
          <Song key={index} track={track} order={index}  />
        ))
      }
    </div>
  );
}

export default Songs;


================================================
File: hooks/useSongInfo.hook.js
================================================
import { useEffect, useState } from 'react';
import { useRecoilState } from 'recoil';

import { currentTrackIdStateAtom } from '../atoms/song.atom';
import useSpotify from './useSpotify.hook';

const useSongInfo = () => {
  const spotifyApi = useSpotify();
  const [currentTrackId, setCurrentTrackId] = useRecoilState(currentTrackIdStateAtom);
  const [songInfo, setSongInfo] = useState(null);

  useEffect(() => {
    const fetchSongInfo = async () => {
      if (currentTrackId) {
        const trackInfo = await fetch(
          `https://api.spotify.com/v1/tracks/${currentTrackId}`,
          {
            headers: {
              Authorization: `Bearer ${spotifyApi.getAccessToken()}`,
            }
          }
        ).then(response => response.json());

        setSongInfo(trackInfo);
      }
    };

    fetchSongInfo();
  }, [currentTrackId, spotifyApi])

  return songInfo;
};

export default useSongInfo;


================================================
File: hooks/useSpotify.hook.js
================================================
import { signIn, useSession } from "next-auth/react";
import { useEffect } from "react";
import spotifyApi from '../lib/spotify';

const useSpotify = () => {
  const { data: session, status } = useSession();

  useEffect(() => {
    if (session) {
      if (session.error === 'RefreshAccessTokenError') {
        signIn();
      }

      spotifyApi.setAccessToken(session.user.accessToken);
    }
  }, [session])

  return spotifyApi;
}

export default useSpotify;


================================================
File: lib/spotify.js
================================================
import SpotifyWebApi from 'spotify-web-api-node';

const scopes = [
  'streaming',
  'playlist-read-private',
  'playlist-read-collaborative',
  'user-library-read',
  'user-top-read',
  'user-read-email',
  'user-read-private',
  'user-read-playback-state',
  'user-read-playback-position',
  'user-read-currently-playing',
  'user-read-recently-played',
  'user-modify-playback-state',
  'user-follow-read',
  'user-follow-modify',
].join(',');

const params = {
  scope: scopes,
};

const queryParamString = new URLSearchParams(params);

const LOGIN_URL = `https://accounts.spotify.com/authorize?${queryParamString.toString()}`;

const spotifyApi = new SpotifyWebApi({
  clientId: process.env.NEXT_PUBLIC_CLIENT_ID,
  clientSecret: process.env.NEXT_PUBLIC_CLIENT_SECRET,
});

export default spotifyApi;

export { LOGIN_URL };



================================================
File: lib/time.js
================================================
const millisecondsToMinutesAndSeconds = (milliseconds) => {
  const minutes = Math.floor(milliseconds / 60000);
  const seconds = ((milliseconds % 60000) / 1000).toFixed(0);

  return seconds == 60 ?
    minutes + 1 + ':00' :
    minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
}

export default millisecondsToMinutesAndSeconds;


================================================
File: pages/_app.tsx
================================================
import { SessionProvider } from 'next-auth/react';
import type { AppProps } from 'next/app';
import { RecoilRoot } from 'recoil';

import '../styles/globals.css';

function MyApp({ Component, pageProps }: AppProps) {
  const { session } = pageProps;
  return (
    <SessionProvider session={session}>
      <RecoilRoot>
        <Component {...pageProps} />
      </RecoilRoot>
    </SessionProvider>
  );
}

export default MyApp



================================================
File: pages/index.tsx
================================================
import type { NextPage } from 'next';
import { getSession } from 'next-auth/react';

import Center from '../components/Center';
import Player from '../components/Player';
import Sidebar from '../components/Sidebar';


const Home: NextPage = () => {
  return (
    <div className='bg-black h-screen overflow-hidden'>
      <main className='flex'>
        {/* Sidebar */}
        <Sidebar />


        {/* Center */}
        <Center />
      </main>

      <div className='sticky bottom-0'>
        <Player />
      </div>
    </div>
  )
}

export default Home;

export const getServerSideProps = async (context: Object) => {
  const session = await getSession(context);

  return {
    props: {
      session
    }
  }
};



================================================
File: pages/login.js
================================================
import { getProviders, signIn } from 'next-auth/react';

const Login = ({ providers }) => {
  return (
    <div className='flex flex-col items-center bg-black min-h-screen w-full justify-center'>
      <img src='https://links.papareact.com/9xl' className='w-52 mb-5' alt='' />

      {
        Object.values(providers).map((provider) => (
          <div key={provider.name} className='p-10 mx-auto'>
            <button onClick={() => signIn(provider.id, { callbackUrl: '/' })}
              className='bg-[#18D860] text-white p-5 rounded-full'>
                Login with {provider.name}
              </button>
          </div>
        ))
      }
    </div>
  );
}

export default Login;

export const getServerSideProps = async () => {
  const providers = await getProviders();

  return {
    props: {
      providers,
    }
  }
}


================================================
File: pages/api/hello.ts
================================================
// Next.js API route support: https://nextjs.org/docs/api-routes/introduction
import type { NextApiRequest, NextApiResponse } from 'next'

type Data = {
  name: string
}

export default function handler(
  req: NextApiRequest,
  res: NextApiResponse<Data>
) {
  res.status(200).json({ name: 'John Doe' })
}



================================================
File: pages/api/auth/[...nextauth].js
================================================
import NextAuth from "next-auth/next";
import SpotifyProvider from 'next-auth/providers/spotify';
import spotifyApi, { LOGIN_URL } from '../../../lib/spotify';

const refreshAccessToken = async (token) => {
  try {
    spotifyApi.setAccessToken(token.accessToken);
    spotifyApi.setRefreshToken(token.refreshToken);

    const { body: refreshedToken } = await spotifyApi.refreshAccessToken();

    return {
      ...token,
      accessToken: refreshedToken.access_token,
      accessTokenExpires: Date.now + refreshedToken.expires_in * 1000, // = 1 hour
      refreshToken: refreshedToken.refresh_token ?? token.refreshToken,
    }

  } catch (error) {
    console.error(error);

    return {
      ...token,
      error: 'RefreshAccessTokenError',
    }
  }
}

export default NextAuth({
  providers: [
    SpotifyProvider({
      clientId: process.env.NEXT_PUBLIC_CLIENT_ID,
      clientSecret: process.env.NEXT_PUBLIC_CLIENT_SECRET,
      authorization: LOGIN_URL,
    }),
  ],
  secret: process.env.JWT_SECRET,
  pages: {
    signIn: '/login',
  },
  callbacks: {
    async jwt({ token, account, user }) {
      // Initial Sign-in
      if (account && user) {
        return {
          ...token,
          accessToken: account.access_token,
          refreshToken: account.refresh_token,
          username: account.providerAccountId,
          accessTokenExpires: account.expires_at * 1000,
        }
      }

      // If not expired
      if (Date.now() < token.accessTokenExpires) {
        return token;
      }

      // AccessToken expired, refresh it...
      return await refreshAccessToken(token);
    },

    async session({ session, token }) {
      session.user.accessToken = token.accessToken;
      session.user.refreshToken = token.refreshToken;
      session.user.username = token.username;

      return session;
    }
  }
});



================================================
File: styles/globals.css
================================================
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
  .button {
    @apply h-5 w-5 cursor-pointer hover:scale-125 transition transform duration-100 ease-out;
  }
}

