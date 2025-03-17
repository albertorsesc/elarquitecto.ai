<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{
  title?: string;
  description?: string;
  keywords?: string;
  canonicalUrl?: string;
  ogType?: string;
  ogImage?: string;
  twitterCard?: string;
}>(), {
  title: 'El Arquitecto A.I.',
  description: 'Democratizando I.A. para el beneficio de Latinoamérica',
  keywords: 'inteligencia artificial, IA, machine learning, español, Latinoamérica',
  canonicalUrl: '',
  ogType: 'website',
  ogImage: '/logo.png',
  twitterCard: 'summary_large_image'
});

// Type the page object with the expected structure
const page = usePage<{
  ziggy: {
    location: string;
  };
}>();

// Make sure we have a string title and include branding if needed
const fullTitle = typeof props.title === 'string' && props.title.includes('El Arquitecto A.I.')
  ? props.title
  : `${props.title || 'Página'} - El Arquitecto A.I.`;

// Format image URL to be absolute if needed
const formatImageUrl = (url: string | null | undefined) => {
  if (!url) {
    return `${window.location.origin}/logo.png`; // Default fallback image
  }

  if (url.startsWith('http')) {
    return url;
  }

  // Make sure the URL starts with a single slash
  const cleanPath = url.startsWith('/') ? url : `/${url}`;
  const baseUrl = window.location.origin;
  return `${baseUrl}${cleanPath}`;
};

const absoluteImageUrl = formatImageUrl(props.ogImage);
const currentPageUrl = props.canonicalUrl || page.props.ziggy.location;

// Make sure description is a non-empty string and not too long
const safeDescription = typeof props.description === 'string' && props.description.trim().length > 0
  ? props.description.slice(0, 160)
  : 'Democratizando I.A. para el beneficio de Latinoamérica';
</script>

<template>
  <Head>
    <title>{{ fullTitle }}</title>
    <meta name="description" :content="safeDescription" />
    <meta name="keywords" :content="keywords" />
    <link rel="canonical" :href="currentPageUrl" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" :content="ogType" />
    <meta property="og:url" :content="currentPageUrl" />
    <meta property="og:title" :content="fullTitle" />
    <meta property="og:description" :content="safeDescription" />
    <meta property="og:image" :content="absoluteImageUrl" />
    <meta property="og:site_name" content="El Arquitecto A.I." />
    <meta property="og:locale" content="es_ES" />

    <!-- Twitter -->
    <meta property="twitter:card" :content="twitterCard" />
    <meta property="twitter:url" :content="currentPageUrl" />
    <meta property="twitter:title" :content="fullTitle" />
    <meta property="twitter:description" :content="safeDescription" />
    <meta property="twitter:image" :content="absoluteImageUrl" />
    <meta name="twitter:site" content="@elarquitectoai" />
  </Head>
</template>